<?php

namespace App\Controller;

use App\Entity\BuoysFiles;
use App\Form\ImportCsvFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ImportController extends AbstractController
{
    /**
     * @Route("/dashboard/import", name="import")
     */
    public function index(Request $request)
    {

        $import = new BuoysFiles();
        $form = $this->createForm(ImportCsvFormType::class, $import);

        // Obtener el formulario
        $form->handleRequest($request);
        // Obtengo los posibles errores del formulario
        $form->getErrors();


        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // Obtengo el archivo
            $filename = $form['filename']->getData();

            // Añadiendo la linea de abajo me permite acceder a las funciones de archivos
            /** @var UploadedFile $foto */
            if ($filename) {

                // Compruebo si es un archivo zip o de otro formato
                $fileType = $filename->getClientMimeType();
                if ($fileType == 'application/zip') {

                    $za = new \ZipArchive();

                    // Movemos el archivo ya renombrado a la carpeta de destino
                    $filename->move(
                        $this->getParameter('buoys_data_folder'),
                        $filename->getClientOriginalName()
                    );
                    $file = $this->getParameter('buoys_data_folder') . '/' . $filename->getClientOriginalName();
                    $za->open($file);
//                    dump($za);

                    for ($i = 0; $i < $za->numFiles; $i++) {
                        echo "index : $i\n";
                        $name = $za->statIndex($i)['name'];
                        $size = $za->statIndex($i)['size'];
                        $comp_size = $za->statIndex($i)['comp_size'];
                        if (strpos($name, '__MACOSX') === false) {
//                            dump($name . ' [ ' . $size . '>' . $comp_size . ']');
                            $za->extractTo($this->getParameter('buoys_data_folder'), $name);
                            // Actualizo el nombre del archivo en BBDD
                            $import->setFilename($name);
                            $import->setSize($size);
                            // Guardo el resto de la info en BBDD
                            $em->persist($import);
                            $em->flush();
                        }
                    }
                    // Elimino el archivo ZIP
                    @unlink($file);
                } else {

                    // Movemos el archivo ya renombrado a la carpeta de destino
                    $filename->move(
                        $this->getParameter('buoys_data_folder'),
                        $filename->getClientOriginalName()
                    );

                    // Actualizo el nombre del archivo en BBDD
                    $import->setFilename($filename->getClientOriginalName());
                    // Obtengo la info del archivo subido
                    $file = $this->getParameter('buoys_data_folder') . '/' . $filename->getClientOriginalName();
                    $absoluteFileSize = filesize($file);
                    $import->setSize($absoluteFileSize);
                    // Guardo el resto de la info en BBDD
                    $em->persist($import);
                    $em->flush();
                }


            }


            // Añado el mensaje de OK
            // redirect to the 'list' view of the given entity ...
            return $this->redirectToRoute('easyadmin', [
                'action' => 'list',
                'entity' => 'User',
            ]);
        }


        return $this->render('dashboard/import.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
