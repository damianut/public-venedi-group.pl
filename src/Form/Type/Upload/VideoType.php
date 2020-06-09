<?php

namespace App\Form\Type\Upload;

use App\Entity\Video;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dir', Type\FileType::class, [
                'constraints' => new Constraints\File([
                    'maxSize' => '100M',
                    'maxSizeMessage' => 'Maksymalny rozmiar pliku to 100 MB.',
                    'mimeTypes' => [
                        'video/mp4',
                    ],
                    'mimeTypesMessage' => 'Nie wysłano filmu w formacie mp4',
                ]),
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'data_class' => Video::class,
        ]);
    }
}
