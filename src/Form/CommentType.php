<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entity = $builder->getData();
        $builder
            ->add('content', null, [
                'help' => 'Minimum 2 characters',
                'attr' => ['placeholder' => "your comment ...",]
            ])
            ->add('send', SubmitType::class, [
                'attr' => [
                    'onclick' => 'sendComment(event)',
                    'data-id' => $options['post_id']
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'post_id' => -1
        ]);

        $resolver->setAllowedTypes('post_id', 'integer');
    }
}
