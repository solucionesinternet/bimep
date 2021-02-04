<?php

namespace App\Controller;

use App\Entity\HistoricSearches;
use App\Entity\Profile;
use App\Entity\Queries;
use App\Entity\Turbines;
use App\Entity\TurbinesDatas;
use App\Entity\TurbinesFiles;
use App\Repository\TurbinesDatasRepository;
use CalendarBundle\CalendarEvents;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Time;
use ZipArchive;


class UserMutrikuController extends AbstractController
{


    /**
     * @Route("/user/dashboard/mutriku", name="user_mutriku")
     */
    public function index(Request $request)
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Obtener el doctrine manager
        $em = $this->getDoctrine()->getManager();

        // Obtenermos el listado de turbinas activas
        $turbines = $em->getRepository(Turbines::class)->findBy(array('active' => 1), array('position' => 'ASC'));
        $turbine = end($turbines);


        if (isset($_POST["formTurbines"])) {

            // Obtengo los parametros de busqueda
            $turbinesId = $request->request->get('formTurbines');
            $daterange = $request->request->get('daterange');
            $fieldType = $request->request->get('fieldType');
            list($dateStart, $dateEnd) = explode("-", $daterange);
            $dStart = new \DateTime($dateStart);
            $dEnd = new \DateTime($dateEnd);
            $dDiff = $dStart->diff($dEnd);

            // Si el número de dias es mayor de 1 ejecuto una consulta y lo ordeno por dias
            // Si es menor o igual a 1 lo muestro por horas
            if ($dDiff->format("%a") > 1) {
                $RAW_QUERY = 'select hour AS hora,  min(' . $fieldType . ') AS minimo, max(' . $fieldType . ') AS maximo, date(date) AS fecha, DATE(timestamp) AS timestamp from turbines_datas  WHERE date(date) BETWEEN \'' . $dateStart . '\' AND \'' . $dateEnd . '\'  AND turbines_id = ' . $turbinesId . ' group by DAY(timestamp) ORDER BY DAY(timestamp)';
                $dateFormat = 'd/m/Y';
            } else {
                $RAW_QUERY = 'select hour AS hora,  min(' . $fieldType . ') AS minimo, max(' . $fieldType . ') AS maximo, date(date) AS fecha, timestamp from turbines_datas  WHERE date(date) BETWEEN \'' . $dateStart . '\' AND \'' . $dateEnd . '\'  AND turbines_id = ' . $turbinesId . ' group by hour(timestamp), day(timestamp) ORDER BY DAY(timestamp), HOUR(timestamp)';
                $dateFormat = 'd/m/Y H:i';
            }
//            echo $RAW_QUERY;
//            die();

            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $presiones = $statement->fetchAll();
            $numPresiones = count($presiones);

            // Recorro el objeto y genero el array
            $total_media = null;
            $horas = null;
            $maximos = null;
            $minimos = null;
            $medias = null;
            $totalAverage = null;
            $linea_medias = null;
            $period = null;

            foreach ($presiones as $item) {
                //$hora = substr($item['hora'], 0, -3);
                $hora = new \DateTime($item['timestamp']);
                $hora = $hora->format($dateFormat);
                $min_pa = $item['minimo'];
                $max_pa = $item['maximo'];

                $horas[] = $hora;
                $minimos[] = intval($min_pa);
                $maximos[] = intval($max_pa);
                $medias[] = (intval($min_pa) + intval($max_pa)) / 2;
                $total_media += ((intval($min_pa) + intval($max_pa)) / 2);
            }

            if ($presiones)
                $totalAverage = number_format(array_sum($medias) / $numPresiones, 2, '.', '');
            else
                $presiones = null;


            if ($presiones != null) {
                $showCharts = 1;
                // Obtener el valor minimo y el maximo
                $pa_min_value = min($minimos);
                $pa_max_value = min($maximos);

            } else {
                $showCharts = 0;
                $minimos = array();
                $pa_min_value = null;
                $pa_max_value = null;
            }


            // El original que funciona es user_dashboard/mutriku2
            return $this->render('user_dashboard/statistics/default.html.twig', [
                'user' => $user,
                'pa_min_value' => $pa_min_value,
                'pa_max_value' => $pa_max_value,
                'turbines' => $turbines,
                'selectedTurbine' => $turbinesId,
                'selectedFieldType' => $fieldType,
                'selectedDaterange' => $daterange,
                'showCharts' => $showCharts,
                'horas' => $horas,
                'minimos' => $minimos,
                'maximos' => $maximos,
                'period' => $period,
                'average' => $medias,
                'totalAverage' => $totalAverage,
                'arrayAverage' => $linea_medias,
                'numPresiones' => $numPresiones
            ]);


        } else {

            // Compruebo si han solicitado ver alguna turbina en concreto y algun periodo en concreto
            if ($request->query->get('view')) {
                $view = $request->query->get('view');
            } else {
                $view = 'day';
            }

            if ($request->query->get('turbine')) {
                $default_turbine_id = $request->query->get('turbine');
            } else {
                $default_turbine_id = $turbine->getId();
            }


            switch ($view) {
                case 'day':
                    $RAW_QUERY = 'select hour AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = CURDATE() AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp)';
                    $dateFormat = 'H:i';
                    $period = new \DateTime();
                    $period = $period->format('d/m/Y');
                    break;
                case 'month':
                    $RAW_QUERY = 'select date AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha, MIN(date) AS fecha_inicio, MAX(date) AS fecha_fin from turbines_datas WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() AND turbines_id = ' . $default_turbine_id . ' group by day(timestamp)';
                    $dateFormat = 'd';
                    break;
                case 'year':
                    $RAW_QUERY = 'select date AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha, MIN(date) AS fecha_inicio, MAX(date) AS fecha_fin from turbines_datas WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() AND turbines_id = ' . $default_turbine_id . ' group by month(timestamp)';
                    $dateFormat = 'm';
                    break;
                default:
                    $RAW_QUERY = 'select hour AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = CURDATE() AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp)';
                    $dateFormat = 'H:i';
                    $period = new \DateTime();
                    $period = $period->format('d/m/Y');
                    break;
            }


            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $presiones = $statement->fetchAll();
            $numPresiones = count($presiones);

            // Recorro el objeto y genero el array
            $total_media = null;
            $horas = null;
            $maximos = null;
            $minimos = null;
            $medias = null;
            $totalAverage = null;
            $linea_medias = null;
            $period = null;

            foreach ($presiones as $item) {
                //$hora = substr($item['hora'], 0, -3);
                $hora = new \DateTime($item['hora']);
                $hora = $hora->format($dateFormat);
                $min_pa = $item['minimo'];
                $max_pa = $item['maximo'];

                $horas[] = $hora;
                $minimos[] = intval($min_pa);
                $maximos[] = intval($max_pa);
                $medias[] = (intval($min_pa) + intval($max_pa)) / 2;
                $total_media += ((intval($min_pa) + intval($max_pa)) / 2);

                if ($view == 'month' || $view == 'year') {
                    $period = $item['fecha_inicio'] . ' > ' . $item['fecha_fin'];
                }
            }
            if ($presiones)
                $totalAverage = number_format(array_sum($medias) / $numPresiones, 2, '.', '');
            else
                $presiones = null;

            // Hago otro bucle para generar la linea de las medias
            if ($presiones) {
                for ($i = 0; $i < count($presiones); $i++) {
                    $linea_medias[] = $totalAverage;
                }
            }


            if ($presiones != null) {
                $showCharts = 1;
                // Obtener el valor minimo y el maximo
                $pa_min_value = min($minimos);
                $pa_max_value = min($maximos);

            } else {
                $showCharts = 0;
                $minimos = array();
                $pa_min_value = null;
                $pa_max_value = null;
            }

            // El original que funciona es user_dashboard/mutriku2
            return $this->render('user_dashboard/statistics/default.html.twig', [
                'user' => $user,
                'pa_min_value' => $pa_min_value,
                'pa_max_value' => $pa_max_value,
                'turbines' => $turbines,
                'selectedTurbine' => $default_turbine_id,
                'showCharts' => $showCharts,
                'view' => trim($view),
                'horas' => $horas,
                'minimos' => $minimos,
                'maximos' => $maximos,
                'period' => $period,
                'average' => $medias,
                'totalAverage' => $totalAverage,
                'arrayAverage' => $linea_medias,
                'numPresiones' => $numPresiones,
                'selectedFieldType' => '',
                'selectedDaterange' => ''
            ]);


        }

    }


    /**
     * @Route("/user/dashboard/mutriku/search", name="user_mutriku_search")
     */
    public function search(Request $request, Session $session)
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        // Guardo la busqueda en la BBDD en la tabla historic_searches
        $turbinesId = $request->request->get('formTurbines');
        $daterange = $request->request->get('daterange');
        $reason = $request->request->get('reason');
        list($dateStart, $dateEnd) = explode("-", $daterange);

        $historic_searches = new HistoricSearches();
        $turbines = $em->getRepository(Turbines::class)->find($turbinesId);

        $historic_searches->setUser($user);
        $historic_searches->setTurbines($turbines);
        $historic_searches->setInitDate(new \DateTime($dateStart));
        $historic_searches->setEndDate(new \DateTime($dateEnd));
        $historic_searches->setReason($reason);

        $em->persist($historic_searches);
        $em->flush();

        // Guardo la busqueda como variable de sesion para obtenrla despues via ajax
        $session->set('historic_searches', $historic_searches);
        $session->set('turbine', $turbines);


        return $this->render('user_dashboard/mutrikuCalendarResults.html.twig', [
            'user' => $user,
            'historic_searches' => $historic_searches
        ]);
    }

    /**
     * @Route("/user/dashboard/mutriku/zipdownload", name="files_zip_download")
     */
    public function download(Request $request, Session $session)
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        // Recogo los objetos
        $filesToDownload = explode(',', $request->request->get('filesToDownload'));
        $historic_searches = $session->get('historic_searches');
        $turbines = $session->get('turbine');
        $turbine = $em->getRepository(Turbines::class)->find($turbines->getId());

        // Recorro los elementos a entregar
        foreach ($filesToDownload as $file) {

            // Obtengo el nombre del archivo y el tamaño del mismo
            $turbinesFiles = $em->getRepository(TurbinesFiles::class)->find($file);
            $fileName = $turbinesFiles->getName();
            $fileSize = $turbinesFiles->getSize();
            $filesToCompres[] = $fileName;
            // Compruebo si ha sido descargado previamente
            $queriesForThisFile = $em->getRepository(Queries::class)->findOneBy(
                array(
                    'turbines' => $turbine->getId(),
                    'user' => $user->getId(),
                    'filename' => $fileName,
                    'historic_search' => $historic_searches->getId()
                )
            );


            // Si esta vacio creo uno nuevo
            if (!$queriesForThisFile) {


                $turbineId = $turbine->getId();
                $turbineDate = 'NOW()';
                $turbineDownloads = 1;
                $turbineFilename = $fileName;
                $turbineSize = $fileSize;
                $turbineActive = 1;
                $turbineCreated = 'NOW()';
                $turbineUserId = $user->getId();
                $TurbinesHistoricSearchId = $historic_searches->getId();

                $RAW_QUERY = "INSERT INTO queries  (turbines_id, date, downloads, filename, size, active, created, user_id, historic_search_id) VALUES  ($turbineId, $turbineDate, $turbineDownloads, '$turbineFilename', $turbineSize, $turbineActive, $turbineCreated, $turbineUserId, $TurbinesHistoricSearchId)";


                $statement = $em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();

            } else {
                // En caso contrario actualizo el numero de descargas

                $queriesForThisFile->setDownloads($queriesForThisFile->getDownloads() + 1);
                $em->persist($queriesForThisFile);
                $em->flush();

            }

            $em->clear();

        }

        // Comprimo los archivos
        $zip = new \ZipArchive();
        $zipName = 'Documents.zip';
        $zip->open($zipName, ZipArchive::CREATE);
        $filesPath = $this->getParameter('turbines_data_historic_folder');
        foreach ($filesToCompres as $file) {
            $zip->addFromString(basename($filesPath . $file), file_get_contents($filesPath . $file));
        }
        $zip->close();
        $response = new Response(file_get_contents($zipName));
        $response = new Response(file_get_contents($zipName));
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($zipName));
        $response->headers->set('Pragma', "no-cache");
        $response->headers->set('Expires', "0");
        $response->headers->set('Content-Transfer-Encoding', "binary");
        $response->sendHeaders();
        $response->setContent(file_get_contents($zipName));

        @unlink($zipName);

        return $response;

    }


    /**
     * @Route("/user/dashboard/mutriku/historics_downloader", name="mutriku_historics_downloader")
     */

    public function historicsDownloader(Request $request, PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();

        // Obtenermos el listado de turbinas activas
        $turbines = $em->getRepository(Turbines::class)->findBy(array('active' => 1), array('position' => 'ASC'));
        $turbine = end($turbines);

        // Compruebo si han solicitado ver alguna turbina en concreto y algun periodo en concreto
        if ($request->query->get('view')) {
            $view = $request->query->get('view');
        } else {
            $view = 'day';
        }

        if ($request->query->get('turbine')) {
            $default_turbine_id = $request->query->get('turbine');
        } else {
            $default_turbine_id = $turbine->getId();
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $em->getRepository(Profile::class)->find($user);
        $numDownloads = $profile->getNumDownloads();

        // Consulta propia para obtener los archivos
        $fileRepository = $em->getRepository(Queries::class);
        $allFilesRepositoryQuery = $fileRepository->createQueryBuilder('f')
            ->where('f.user = :user')
            ->groupBy('f.filename')
            ->setParameter('user', $user->getId())
            ->getQuery();

        // Paginate the results of the query
        $pagination = $paginator->paginate(
        // Doctrine Query, not results
            $allFilesRepositoryQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

//        dump($pagination);
//        die();

        return $this->render('user_dashboard/mutriku_historics_download.html.twig', [
            'user' => $user,
            'pagination' => $pagination,
            'numDownloads' => $numDownloads,
            'turbines' => $turbines,
            'selectedTurbine' => $default_turbine_id,
            'view' => trim($view),
        ]);


    }


    /**
     * @Route("/user/dashboard/historicdownload/{id}", name="mutriku_file_download")
     */
    public function downloadFile(Queries $queries, Request $request)
    {

        if ($request->isXmlHttpRequest()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();


            dump($queries);

            $queries->setDownloads($queries->getDownloads() + 1);
            $em->persist($queries);
            $em->flush();


            // Mando el archivo a descargar
            $file = $this->getParameter('turbines_data_historic_folder') . $queries->getFilename();
            $response = new Response();
            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', mime_content_type($file));
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($file) . '";');
            $response->headers->set('Content-length', filesize($file));
            $response->headers->set('Pragma', "no-cache");
            $response->headers->set('Expires', "0");
            $response->headers->set('Content-Transfer-Encoding', "binary");

            // Send headers before outputting anything
            $response->sendHeaders();
            $response->setContent(file_get_contents($file));
            return $response;


        } else {
            return $this->redirect($this->generateUrl('user_mutriku'));
        }


    }

}
