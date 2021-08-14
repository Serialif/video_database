<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Video;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 2,
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Description',
                    'constraints' => [
                        new NotBlank(),
                        new Length(
                            [
                                'min' => 2,
                            ]
                        )
                    ]
                ]
            )
            ->add(
                'url',
                UrlType::class,
                [
                    'label' => 'Url',
                    'constraints' => [
                        new NotBlank(),
                        new Url()
                    ]
                ]
            )
            ->add(
                'tags',
                EntityType::class,
                [
                    'label' => 'Mots clés',
                    'class' => Tag::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'attr' => [
                        'style' => 'overflow: hidden'
                    ]
                //                    'expanded' => true,
                //                    'by_reference' => false,
                //                    'query_builder' => function (EntityRepository $entityRepository) {
                //                        return $entityRepository->createQueryBuilder('t')
                //                            ->orderBy('t.name', 'ASC');
                //                    },
                ]
            );
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
