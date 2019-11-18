<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'label' => 'Title',
                'attr' => [ 'placeholder' => 'A catchy title...']
            ])
            ->add('short', null, [
                'label' => 'Short',
                'help' => '0/'.BlogPost::MAX_SHORT,
                'attr' => [
                    'placeholder' => 'Not much than 200 characters...',
                    'rows' => 5,
                    'data-max' => BlogPost::MAX_SHORT,
                    'onkeyup' => 'shortHelper(this)'
                ]
            ])
            ->add('content', CKEditorType::Class)
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
