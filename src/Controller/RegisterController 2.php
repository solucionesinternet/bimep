<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\User;
use App\Form\RegisterUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        // Creo una instancia de la Entity User
        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);

        // Recojo el objeto recibido del formulario
        $form->handleRequest($request);
        //Compruebo que se haya enviado
        if ($form->isSubmitted() && $form->isValid()){


            // Encripto la contraseña
            $user->setPassword($passwordEncoder->encodePassword($user, $form['password']->getData()));

            // Genero uns instancia del Entity Manager que me permite interactuar con la BBDD
            $em = $this->getDoctrine()->getManager();

            // Defino el perfil por defecto
            $profile = $em->getRepository(Profile::class)->findBy(array('name' => 'Tecnólogo'));
            $user->setProfile($profile[0]);

            // Guardo el objeto recogido del formulario en la BBDD
            $em->persist($user);
            $em->flush();

            // Mostrar mensaje de usuario agrefgado correctamente
            $this->addFlash('alertas', User::USUARIO_CREADO_CORRECTAMENTE);

            // Redirijo a de nuevo al formulario
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'funciona'
        ]);
    }
}
