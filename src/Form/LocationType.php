<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Translation\TranslatorInterface;

class LocationType extends AbstractType
{
    private $ti;

    public function __construct(TranslatorInterface $ti)
    {
        $this->ti = $ti;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['hidden'])
        {
            $builder
                ->add('type', HiddenType::class )
                ->add('name', HiddenType::class )
                ->add('city', HiddenType::class )
                ->add('country', HiddenType::class )
                ->add('countryCode', HiddenType::class )
                ->add('administrative', HiddenType::class )
                ->add('county', HiddenType::class )
                ->add('suburb', HiddenType::class )
                ->add('lat', HiddenType::class )
                ->add('lng', HiddenType::class)
                ->add('postcode', HiddenType::class)
                ->add('value')
            ;   
        } else {
            $builder
                ->add('type', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('name', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('city', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('country', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('countryCode', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('administrative', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('county', TextType::class, ['attr' => ['readonly' => true]])
                ->add('suburb', TextType::class, ['attr' => ['readonly' => true]])
                ->add('lat', TextType::class, ['attr' => ['readonly' => true]])
                ->add('lng', TextType::class, ['attr' => ['readonly' => true]])
                ->add('postcode', TextType::class, ['attr' => ['readonly' => true]] )
                ->add('value', TextType::class)
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
            'hidden' => false
        ]);

        $resolver->setAllowedTypes('hidden', 'bool');
    }
}
