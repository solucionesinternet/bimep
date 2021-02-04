<?php

namespace App\Controller;

use App\Entity\BuoysFiles;
use App\Entity\BuoysFilesUsers;
use App\Entity\Profile;
use App\Entity\Turbines;
use App\Repository\BuoysFilesUsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserBimepController extends AbstractController
{
    /**
     * @Route("/user/dashboard/bimep", name="user_bimep")
     */
    public function index(Request $request, PaginatorInterface $paginator)
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

        // Obtener el numero de descargar permitidas
        $profile = $em->getRepository(Profile::class)->find($user);

        $numDownloads = $profile->getNumDownloads();


        // Consulta propia para obtener los archivos
        $fileRepository = $em->getRepository(BuoysFiles::class)->listFiles($user->getId());


        // Paginate the results of the query
        $pagination = $paginator->paginate(
        // Doctrine Query, not results
            $fileRepository,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );


        return $this->render('user_dashboard/bimep.html.twig', [
            'user' => $user,
            'pagination' => $pagination,
            'numDownloads' => $numDownloads
        ]);
    }

    /**
     * @Route("/user/dashboard/bimep/download/{id}", name="file_download")
     */
    public function download(BuoysFiles $buoysFiles, Request $request)
    {

        if ($request->isXmlHttpRequest()) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();

            $em = $this->getDoctrine()->getManager();


            // Comprobar si ya lo ha descargado previamente
            $buoys_files_users_check = $this->getDoctrine()->getRepository(BuoysFilesUsers::class)->findOneBy(['user' => $user, 'buoys_files' => $buoysFiles]);
            // Si esta vacio metemos uno nuevo en la tabla y sino actualizamos
            if ($buoys_files_users_check === null) {
                $buoys_files_users = new BuoysFilesUsers();
                $buoys_files_users->setUser($user);
                $buoys_files_users->setDownloads(1);
                $buoys_files_users->setCreated(new \DateTime());
                $buoys_files_users->setModified(new \DateTime());
                $buoys_files_users->setBuoysFiles($buoysFiles);
                $em->persist($buoys_files_users);

            } else {
                $buoys_files_users_check->setModified(new \DateTime());
                $buoys_files_users_check->setDownloads($buoys_files_users_check->getDownloads() + 1);
                $em->persist($buoys_files_users_check);
            }
            // Actualizo la descarga general
            $buoysFiles->setDownloads($buoysFiles->getDownloads() + 1);
            $em->persist($buoysFiles);
            $em->flush();

            // Mando el archivo a descargar
            $file = $this->getParameter('buoys_data_folder') . $buoysFiles->getFilename();
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
            return $this->redirect($this->generateUrl('user_bimep'));
        }

    }
}
