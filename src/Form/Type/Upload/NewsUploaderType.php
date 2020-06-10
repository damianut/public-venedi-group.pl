<?php

namespace App\Form\Type\Upload;

use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsUploaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('title', Type\TextType::class, [
                 'label' => 'Tytuł artykułu:',
             ])
             ->add('news_content', Type\TextType::class, [
                 'label' => 'Treść:',
             ])
             ->add('main_photo', Type\FileType::class, [
                 'label' => 'Zdjęcie tytułowe:',
                 'mapped' => false,
                 'required' => false,
             ])
            ->add('photosAlbum', PhotosAlbumUploaderType::class, [
                'label' => false,
            ])
            ->add('videosAlbum', VideosAlbumUploaderType::class, [
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
            'data_class' => News::class,
        ]);
    }
}
