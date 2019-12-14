<?php

namespace App\Form;

use App\Entity\BlogPost;
use App\Form\GalleryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('title', null, [
                'label' => 'Title',
                'attr' => [ 'placeholder' => 'A catchy title...']
            ])
            ->add('short', null, [
                'label' => 'Short',
                'help' => strlen($entity->getShort()).'/'.BlogPost::MAX_SHORT,
                'attr' => [
                    'placeholder' => 'Not much than 200 characters...',
                    'rows' => 5,
                    'data-max' => BlogPost::MAX_SHORT
                ]
            ])
            ->add('gallery', GalleryType::class, [
                'label' => 'Image Gallery',
                'help' => 'add the images of the Blog post, multiples files upload is available'
            ])
            ->add('content', CKEditorType::class)
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn-primary btn-block']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BlogPost::class,
        ]);
    }
}
