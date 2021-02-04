<?php

namespace App\Form;

use App\Entity\Buoys;
use App\Entity\BuoysFiles;
use App\Entity\FilesCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportCsvFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('buoys',EntityType::class,[
                'class' => Buoys::class,
                'choice_label' => 'name',
                'label' => 'Boya:',
                'required' => true,
                'attr' => array('class' => 'select2')
            ])
            ->add('files_categories',EntityType::class,[
                'class' => FilesCategories::class,
                'choice_label' => 'category',
                'label' => 'Categoría:',
                'required' => true,
                'attr' => array('class' => 'select2')
            ])
            ->add('date_start', DateType::class,
                [
                    'attr' => ['class' => ''],
                    'label' => "Fecha Inicio:",
                    'required' => true,
                    'widget' => 'single_text',
                    'attr' => array('class' => 'form-control')
                ])
            ->add('date_end', DateType::class,
                [
                    'label' => "Fecha Fin:",
                    'required' => true,
                    'widget' => 'single_text',
                    'attr' => array('class' => 'form-control')
                ])
            ->add('filename', FileType::class,
                [
                    'attr' => ['class' => ''],
                    'label' => "Seleccionar archivo:",
                    'help' => "Aceptados archivos zip, xls y xlsx",
                    'required' => true,
                    'attr' => array('class' => 'form-control'),
                    'constraints' => [
                        new File([
                            'maxSize' => '99999999999',
                            'mimeTypes' => [
                                'application/vnd.ms-excel',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'application/zip',
                            ],
                            'mimeTypesMessage' => 'Por favor suba un archivo de Excel o Zip',
                        ])
                    ]
                ])
            ->add('guardar', SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-success btn-lg float-right'],
                    'label' => "Importar archivo",
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BuoysFiles::class,
        ]);
    }
}
