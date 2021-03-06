<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $tr;

    public function __construct(TranslatorInterface $tr)
    {
        $this->tr = $tr;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->tr->trans('contact.name.placeholder')],
                'constraints' => [
                    new NotBlank(["message" => $this->tr->trans('contact.name.not-blank')])
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->tr->trans('contact.subject.placeholder')],
                'constraints' => [
                    new NotBlank(["message" => $this->tr->trans('contact.subject.not-blank')])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => $this->tr->trans('contact.email.placeholder')],
                'constraints' => [
                    new NotBlank(["message" => $this->tr->trans('contact.email.not-blank')]),
                    new Email(["message" => $this->tr->trans('contact.email.not-email')]),
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $this->tr->trans('contact.message.placeholder'),
                    'rows' => 15
                ],
                'constraints' => [
                    new NotBlank(["message" => $this->tr->trans('contact.message.not-blank')])
                ]
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'error_bubbling' => true
        ));
    }

    public function getName()
    {
        return 'contact_form';
    }
}