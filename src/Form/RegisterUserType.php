<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('name_surnames', TextType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Nombre y Apellidos:",
//                ])
//            ->add('email', EmailType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Email:",
//                ])
//            ->add('telephone', TextType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Teléfono:",
//                ])
//            ->add('particular', CheckboxType::class,
//                [
//                    'required' => false,
//                    'label' => "Soy un particular",
//                    'attr' => ['data-id' => 'RegisterUserParticular'],
//                ])
//            ->add('company', TextType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Empresa:",
//                ])
//            ->add('address', TextType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Dirección:",
//                ])
//            ->add('cif', TextType::class,
//                [
//                    'required' => true,
//                    'attr' => ['class' => 'form-control'],
//                    'label' => "Cif:",
//                ])
//            ->add('password', RepeatedType::class, [
//
//                'type' => PasswordType::class,
//                'required' => true,
//                'first_options' => ['attr' => ['class' => 'form-control'], 'label' => 'Contraseña:'],
//                'second_options' => ['attr' => ['class' => 'form-control'], 'label' => 'Repetir contraseña:'],
//            ])
//
//            ->add('condiciones', CheckboxType::class,
//                [
//                    'required' => false,
//                    'mapped' => false,
//                    'label' => "Acepto las Política de Cookies ,  Aviso legal  y  Política de privacidad",
//                    'attr' => ['data-id' => 'RegisterUserParticular'],
//                ])
//            ->add('registrarse', SubmitType::class,
//                [
//                    'attr' => ['class' => 'btn btn-primary btn-lg float-left'],
//                    'label' => "Registrarse",
//                ])
//        ;
//    }


        $builder->add(
            $builder->create('Datos_personales', FormType::class, array('inherit_data' => true))
                ->add('name_surnames', TextType::class,
                    [
                        'required' => true,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Nombre y Apellidos:",
                    ])
                ->add('email', EmailType::class,
                    [
                        'required' => true,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Email:",
                    ])
                ->add('telephone', TextType::class,
                    [
                        'required' => true,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Teléfono:",
                    ])
                ->add('particular', CheckboxType::class,
                    [
                        'required' => false,
                        'label' => "Soy un particular",
                        'attr' => ['data-id' => 'RegisterUserParticular'],
                        'attr' => ['class' => 'user_particular'],
                    ])
        );



        $builder->add(
            $builder->create('Datos_empresa', FormType::class, array(
                'inherit_data' => true,
                'row_attr' => ['class' => 'group_company']
            ))
                ->add('company', TextType::class,
                    [
                        'required' => false,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Empresa:",
                    ])
                ->add('address', TextType::class,
                    [
                        'required' => false,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Dirección:",
                    ])
                ->add('cif', TextType::class,
                    [
                        'required' => false,
                        'attr' => ['class' => 'form-control'],
                        'label' => "Cif:",
                    ])

        );

        $builder
            ->add('password', RepeatedType::class, [

                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['attr' => ['class' => 'form-control'], 'label' => 'Contraseña:'],
                'second_options' => ['attr' => ['class' => 'form-control'], 'label' => 'Repetir contraseña:'],
            ])
            ->add('condiciones', CheckboxType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'label' => "Acepto las Política de Cookies ,  Aviso legal  y  Política de privacidad",
                    'attr' => ['data-id' => 'RegisterUserParticular'],
                ])
            ->add('registrarse', SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary btn-lg float-left'],
                    'label' => "Registrarse",
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
