<?php

namespace App\Controller;

use App\Entity\HistoricSearches;
use App\Entity\Profile;
use App\Entity\Queries;
use App\Entity\Turbines;
use App\Entity\TurbinesDatas;
use App\Entity\TurbinesFiles;
use App\Entity\TurbineToProfile;
use App\Repository\TurbinesDatasRepository;
use CalendarBundle\CalendarEvents;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\AreaChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\LineChart;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Time;
use ZipArchive;
use Doctrine\ORM\Query\Expr;


class UserMutrikuController extends AbstractController
{


    /**
     * @Route("/user/dashboard/mutriku", name="user_mutriku")
     */
    public function index(Request $request)
    {

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profileId = $user->getProfile();



        // Obtener el doctrine manager
        $em = $this->getDoctrine()->getManager();

        // Obtenemos el listado de turbinas a las que tiene acceso este prfil
        $turbinesToProfile = $em->getRepository(TurbineToProfile::class)->findBy(array('profile' => $profileId));

        // Obtenermos el listado de turbinas activas para este tipo de perfil
//        $qb = $em->createQueryBuilder();
//        $qb
//            ->select('t')
//            ->from('App\Entity\Turbines', 't')
//            ->from('App\Entity\TurbineToProfile', 'tp')
////            ->leftJoin(
////                'App\Entity\TurbineToProfile',
////                'tp',
////                Expr\Join::WITH,
////                'tp.profile = :profile_id'
////            )
//            ->where('t.active = 1')
//            ->where('tp.profile = :profile_id')
//            ->setParameter('profile_id', $user->getProfile())
//            ->orderBy('t.number', 'ASC');
//        $turbines = $qb->getQuery()->getResult();
        $turbines = $em->getRepository(Turbines::class)->findBy(array('active' => 1), array('number' => 'ASC'), array('ID' => $turbinesToProfile));
        $turbine = reset($turbines);


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
//                    $RAW_QUERY = 'select hour AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = CURDATE() AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp)';
                    $RAW_QUERY = 'select DISTINCT TIME(hour) AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp) ORDER BY ID ASC  ';
                    $dateFormat = 'H:i';
                    $period = new \DateTime();
                    $period = $period->format('d/m/Y');
                    break;
                case 'month':
                    $RAW_QUERY = 'select DISTINCT date AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha, MIN(date) AS fecha_inicio, MAX(date) AS fecha_fin from turbines_datas WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() AND turbines_id = ' . $default_turbine_id . ' group by day(timestamp) ORDER BY ID ASC  ';
                    $dateFormat = 'd';
                    break;
                case 'year':
                    $RAW_QUERY = 'select DISTINCT date AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha, MIN(date) AS fecha_inicio, MAX(date) AS fecha_fin from turbines_datas WHERE date BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW() AND turbines_id = ' . $default_turbine_id . ' group by month(timestamp) ORDER BY ID ASC  ';
                    $dateFormat = 'm';
                    break;
                default:
//                    $RAW_QUERY = 'select DISTINCT hour AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = CURDATE() AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp)';
                    $RAW_QUERY = 'select DISTINCT hour AS hora,  min(pressure_pa) AS minimo, max(pressure_pa) AS maximo, date(date) AS fecha from turbines_datas  WHERE date(date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND turbines_id = ' . $default_turbine_id . ' group by hour(timestamp) ORDER BY ID ASC  ';
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
            'historic_searches' => $historic_searches,
            'selectedTurbineId' => $turbinesId
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
        $customFields = explode(',', $request->request->get('CustomFieldsfilesToDownload'));
        $numCustomFields = count($customFields);
        $historic_searches = $session->get('historic_searches');
        $turbines = $session->get('turbine');
        $turbine = $em->getRepository(Turbines::class)->find($turbines->getId());

        // Si quieren campos personalizados hacemos una consulta a BBDD
        if ($numCustomFields > 1) {

            // Recorro los archivos seleccionados para obtener las fechas
            foreach ($filesToDownload as $file) {

                $turbinesFiles = $em->getRepository(TurbinesFiles::class)->find($file);
                $fileName = $turbinesFiles->getName();
                $turbinesName = $turbinesFiles->getTurbines();
                $turbinesId = $turbines->getId();
                $completeTurbineNumber = substr($fileName, 0, 3);
                $DateWithoutFormat = substr($fileName, 4, 8);

                // En base a la fecha de la cadena del nombre del archivo genero un array con las fechas en las que buscar
                $datetime = \DateTime::createFromFormat("Ymd",$DateWithoutFormat);
                $selectedDates[] = $datetime->format('Y/m/d');

            }
            //echo print_r($selectedDates);

            // Ejecuto la consulta
            $fields = $request->request->get('CustomFieldsfilesToDownload');
            $datesStr = implode("', '", $selectedDates);

            // Gereno la ruta de a donde exportar el archivo desde el propio MySql
            $mysqExportTmpCsvFile = $this->getParameter('mysql_export_csv_folder').$this->getParameter('mysql_export_csv_file');

            // Genero la cabecera del CSV en la propia consulta de MySql
            $fieldsArray = explode(",",$fields);
            $fieldsTxt = implode("', '", $fieldsArray);
            $fieldsHeaser = "SELECT 'tuebine_name','date','hour','".$fieldsTxt."' UNION ALL ";
            $RAW_QUERY = $fieldsHeaser."SELECT '$turbines' AS tuebine_name, date, hour, $fields INTO OUTFILE '".$mysqExportTmpCsvFile."' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM turbines_datas WHERE turbines_id = ".$turbinesId." AND date IN ('$datesStr')  ";
 //           $RAW_QUERY = "SELECT '$turbines' AS tuebine_name, date, hour, $fields  FROM turbines_datas WHERE turbines_id = ".$turbinesId." AND date IN ('$datesStr') ";

            //echo $RAW_QUERY;
            $statement = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();

            // Una vez generado el archivo lo muevo para reaignarle el propietario
            $mysqExportTmpCsvFile2 = $this->getParameter('mysql_export_csv_folder')."tmpcsv.csv";
            copy($mysqExportTmpCsvFile, $mysqExportTmpCsvFile2);
            @unlink($mysqExportTmpCsvFile);
            rename($mysqExportTmpCsvFile2, $mysqExportTmpCsvFile);

            // Esto lo comento porque ahora genero el CSV desde MySql y lo guardo directamente en la carpeta /tmp/mysql_export/
//            // Le paso el parametro fetch_num para que no meta el nombre de la colunma dentro del array
//            $result_data = $statement->fetchAll(\PDO::FETCH_NUM);
//
//            $fieldsArray1 = array('tuebine_name','date','hour');
//            $fieldsArray2 = explode(",",$fields);
//            for($i = 0; $i < count($fieldsArray2); $i++){
//                array_push($fieldsArray1, trim($fieldsArray2[$i]));
//            }
//            $fieldsArray = $fieldsArray1;
//            // Set the content type
//            header('Content-type: text/csv');
//            header('Content-Disposition: attachment; filename=Turbine.csv');
//            header("Content-Transfer-Encoding: UTF-8");
//
//            $output = fopen("php://output",'a') or die("Can't open php://output");
//
//            fputcsv($output, $fieldsArray);
//            foreach($result_data as $product) {
//                $arraydatos = array();
//                for($i = 0; $i < count($fieldsArray); $i++){
//                    array_push($arraydatos, $product[$i]);
//                }
//                fputcsv($output, $arraydatos);
//                unset($arraydatos);
//
//            }
//
//            fclose($output) or die("Can't close php://output");
//            die();

            // Comprimo los archivos
            $zip = new \ZipArchive();
            $zipName = 'CustomFieldsCsv.zip';
            $zip->open($zipName, ZipArchive::CREATE);
            $filesPath = $this->getParameter('mysql_export_csv_folder');
                $zip->addFromString(basename($mysqExportTmpCsvFile), file_get_contents($mysqExportTmpCsvFile));
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
            @unlink($this->getParameter('mysql_export_csv_folder')."turbine.csv");

            return $response;



        } else {
            // En caso de no querer campos personalizados descargamos los archivos comprimidos

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
                //Obtengo la carpeta del historico correspondiente a esta turbina
                $completeTurbineNumber = substr($file, 0, 3);
                $zip->addFromString(basename($filesPath . $completeTurbineNumber . "/" . $file), file_get_contents($filesPath . $completeTurbineNumber . "/" . $file));
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


    }


    /**
     * @Route("/user/dashboard/mutriku/historics_downloader", name="mutriku_historics_downloader")
     */

    public function historicsDownloader(Request $request, PaginatorInterface $paginator)
    {

        $em = $this->getDoctrine()->getManager();

        // Obtenermos el listado de turbinas activas
        $turbines = $em->getRepository(Turbines::class)->findBy(array('active' => 1), array('number' => 'ASC'));
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
            $completeTurbineNumber = substr($queries->getFilename(), 0, 3);
            $file = $this->getParameter('turbines_data_historic_folder') . $completeTurbineNumber . '/' . $queries->getFilename();
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
