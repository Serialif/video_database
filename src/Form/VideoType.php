<?php

namespace App\Form;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre'
                ]
            )
            ->add('description',
                  TextareaType::class,
                  [
                      'label' => 'Description'
                  ]
            )
            ->add('url',
                  UrlType::class,
                  [
                      'label' => 'Url'
                  ]
            )
            ->add('image',
                  TextType::class,
                  [
                      'label' => 'Image'
                  ]
            )
//            ->add('createdAt',
//                  DateType::class,
//                  [
//                      'label' => 'Date de création',
//                      'widget' => 'single_text'
//                  ]
//            )
//            ->add('updatedAt',
//                  DateType::class,
//                  [
//                      'label' => 'Date de modification',
//                      'widget' => 'single_text'
//                  ]
//            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Video::class,
            ]
        );
    }
}
