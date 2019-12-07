<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\EventTag;
use App\Entity\Team;
use App\Form\LocationType;
use App\Repository\EventTagRepository;
use Symfony\Component\Translation\TranslatorInterface;

class EventType extends AbstractType
{
    private $ti;

    public function __construct(TranslatorInterface $ti)
    {
        $this->ti = $ti;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('team', EntityType::class, [
                'label' => $this->ti->trans('event.add.team.label'),
                'class' => Team::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('tag', EntityType::class, [
                'label' => $this->ti->trans('event.add.tag.label'),
                'class' => EventTag::class,
                'query_builder' => function (EventTagRepository $rep) {
                    return $rep->queryActivated();
                },
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => false,
                'block_prefix' => 'tag_choice'
            ])
            ->add('name', null, [
                'label' => $this->ti->trans('event.add.name.label')
            ])
            ->add('start', DateTimeType::class, [
                'label' => $this->ti->trans('event.add.start.label'),
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                //'date_format' => 'MM/dd/yyyy h:mm a',
                'empty_data' => '',
            ])
            ->add('finish', DateTimeType::class, [
                'label' => $this->ti->trans('event.add.finish.label'),
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                //'date_format' => 'MM/dd/yyyy h:mm a'
            ])
            ->add('registerFinish', DateTimeType::class,[
                'label' => $this->ti->trans('event.add.register-finish.label'),
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'required' => false,
                //'date_format' => 'MM/dd/yyyy h:mm a'
            ])
            ->add('info', null, [
                'label' => $this->ti->trans('event.add.info.label'),
                'required' => false
            ])
            ->add('maxPlayers', null, [
                'label' => $this->ti->trans('event.add.max-player.label'),
                'required' => false
            ])
            ->add('location', LocationType::class, [
                'label' => $this->ti->trans('event.add.location.label'),
                'hidden' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
