<?php

namespace App\Form;

use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\PersonnalStatType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParticipationStatsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $formEvent) {
                $form = $formEvent->getForm();
                $entity = $formEvent->getData(); //should be an instance of participation 
                $team = $entity->getPlayer()->getTeam();

                $form->add('stats', CollectionType::class, [
                    'entry_type' => PersonnalStatType::class,
                    'entry_options' => [
                        'team' => $team,
                        'label' => false,
                        'inEvent' => true
                    ],
                    'allow_add' => true,
                    'by_reference' => false,
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}
