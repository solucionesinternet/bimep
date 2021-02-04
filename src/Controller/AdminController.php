<?php


namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Turbines;
use App\Entity\TurbineToProfile;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends EasyAdminController
{
    /** @var array The full configuration of the entire backend */
    protected $config;
    /** @var array The full configuration of the current entity */
    protected $entity;
    /** @var Request The instance of the current Symfony request */
    protected $request;
    /** @var EntityManager The Doctrine entity manager for the current entity */
    protected $em;

    /**
     * AdminController constructor.
     * @param array $config
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        if (isset($_POST['user']['password']['first'])) {
            $user = new User();
            $plainPassword = $_POST['user']['password']['first'];
            $encoded = $passwordEncoder->encodePassword($user, $plainPassword);
            $_POST['user']['password']['first'] = $encoded;
            $_POST['user']['password']['second'] = $encoded;
        }
    }






    protected function updateUserEntity($entity)
    {
        $entity->setModified(new \DateTime());
        $entity->setPassword($_POST['user']['password']['first']);
        parent::updateEntity($entity);
    }


    protected function updateFilesCategoriesEntity($entity)
    {
        $entity->setModified(new \DateTime());
        parent::updateEntity($entity);
    }


    protected function persistTurbinesEntity($entity)
    {


        dump($_POST['turbines']['theMappings']);
        // Obtengo los perfiles seleccionados
        $profiles = $_POST['turbines']['theMappings'];
        $this->persistEntity($entity);
        $turbineId = $entity->getId();

        foreach ($profiles as $profileId) {

            $turbineToProfile = new TurbineToProfile();

            $profileObject = $this->getDoctrine()->getRepository(Profile::class)->find($profileId);
            $turbineObject = $this->getDoctrine()->getRepository(Turbines::class)->find($turbineId);

            $turbineToProfile->setProfile($profileObject);
            $turbineToProfile->setTurbines($turbineObject);

            $this->persistEntity($turbineToProfile);
        }


        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', [
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ]);

    }

    protected function updateTurbinesEntity($entity)
    {
        $id = $entity->getId();
        $profiles = $_POST['turbines']['theMappings'];

        // Primero elimino todos los perfiles asociados a esta turbina
        $profiles_to_delete = $this->getDoctrine()->getRepository(TurbineToProfile::class)->findBy(array('turbines' => $entity->getId()));
        foreach ($profiles_to_delete as $single_to_delete) {
            $this->removeEntity($single_to_delete);
        }


        foreach ($profiles as $profileId) {

            $turbineToProfile = new TurbineToProfile();

            $profileObject = $this->getDoctrine()->getRepository(Profile::class)->find($profileId);
            dump($profileObject);
            $turbineObject = $this->getDoctrine()->getRepository(Turbines::class)->find($entity->getId());
            dump($turbineObject);

            $turbineToProfile->setProfile($profileObject);
            // $turbineToProfile->setProfile($profileId);
            // $turbineToProfile->setTurbines($entity->getId());
            $turbineToProfile->setTurbines($turbineObject);

            $this->persistEntity($turbineToProfile);
        }

        parent::updateEntity($entity);
    }
//
//    protected function createEditForm($entity, array $parameters = ['yannick','B']){
//        dump($parameters);
//        die();
//
//    }


    /**
     * Used to add/modify/remove parameters before passing them to the Twig template.
     * If the controller implements an action specific method (e.g. renderEditTemplate)
     * it will be used
     *
     * @param string $actionName The name of the current action (list, show, new, etc.)
     * @param string $templatePath The path of the Twig template to render
     * @param array $parameters The parameters passed to the template
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderTemplate($actionName, $templatePath, array $parameters = [])
    {
        $customRenderMethod = 'render' . ucfirst($actionName) . 'Template';

        if (method_exists($this, $customRenderMethod)) {
            return $this->$customRenderMethod($actionName, $templatePath, $parameters);
        }

        // Verifico que estamos editando una Turbina y le paso los parametros y muestro la plantilla personalizada
        if ($actionName == 'edit' && $parameters['entity'] instanceof Turbines) {
            // Obtengo los items seleccionados
            $query = $this->getDoctrine()
                ->getRepository(TurbineToProfile::class)
                ->createQueryBuilder('t')
                ->select('IDENTITY(t.profile)')// El parametro IDENTITY permite seleccionar un FK sin hacer un join
                ->where('t.turbines = :turbine_id')
                ->setParameter('turbine_id', $parameters['entity']->getId())
                ->getQuery();
            $result = $query->getArrayResult($query);
            dump($result);
            foreach ($result as $element) {
                $array_profiles[] = $element[1];
            }
            $selected_profiles = implode(',', $array_profiles);
            // Como no quiero un array asociativo lo convierto a un array simple
            //$ids = array_map('current', $result);
            $parameters['selected_profiles'] = $selected_profiles;
            $templatePath = 'dashboard/turbines_edit.html.twig';
        }

        // return $this->render($templatePath, $parameters);
        return $this->render($templatePath, $parameters);
    }

}

?>