<?php

namespace btba\ChatBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class ChatMessageType extends AbstractType
{
    private $trans;
    private $message_class;

    public function __construct(String $message_class, TranslatorInterface $trans)
    {
        $this->trans = $trans;
        $this->message_class = $message_class;
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
            'data_class' => $this->message_class,
        ]);
    }
}
