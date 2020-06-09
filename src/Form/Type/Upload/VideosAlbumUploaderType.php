<?php

namespace App\Form\Type\Upload;

use App\Entity\VideosAlbum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideosAlbumUploaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', Type\TextType::class, [
                'label' => 'Tytuł albumu:',
            ])
            ->add('videos', Type\CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => [
                    'label' => false,
                ],
                'entry_type' => VideoType::class,
                'label' => false,
            ])
            ->add('password', Type\PasswordType::class, [
                'label' => 'Hasło:',
                'mapped' => false,
            ])
            ->add('submit', Type\SubmitType::class, [
                'label' => 'WYŚLIJ',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'novalidate' => 'novalidate',
            ],
            'by_reference' => false,
            'data_class' => VideosAlbum::class,
        ]);
    }
}
