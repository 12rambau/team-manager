<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numStreet', HiddenType::class)
            ->add('street', HiddenType::class)
            ->add('zipcode', HiddenType::class)
            ->add('city', HiddenType::class)
            ->add('region', HiddenType::class)
            ->add('country', HiddenType::class)
            ->add('lat')
            ->add('lng')
            ->add('fullAdr')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
