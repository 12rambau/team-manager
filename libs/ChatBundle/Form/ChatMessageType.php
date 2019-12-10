<?php

namespace bornToBeAlive\ChatBundle\Form;

use bornToBeAlive\ChatBundle\Entity\ChatMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class ChatMessageType extends AbstractType
{
    private $trans;

    public function __construct(TranslatorInterface $trans)
    {
        $this->trans = $trans;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', null, [
                'attr'=> ['placeholder' => $this->trans->trans('placeholder',[],'chat')]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChatMessage::class,
        ]);
    }
}
