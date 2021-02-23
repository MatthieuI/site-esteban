<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\AppointmentType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $appointments = array();
        foreach($options['appointment_types'] as $type) {
            $appointments[$type->getType()] = $type->getId();
        }

        $builder
            ->add('appointmentType', ChoiceType::class, [
                'choices'  => $appointments,
                'label' => 'Type de rendez-vous'
            ])
            ->add('appointmentTime', ChoiceType::class, [
                'choices'  => $options['available_times'],
                'label' => 'Heure'
            ])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('birthDate', BirthdayType::class, ['label' => 'Date de naissance'])
            ->add('phone', TextType::class, ['label' => 'Numéro de téléphone'])
            ->add('mail', EmailType::class, ['label' => 'Adresse mail'])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-dark my-0']
            ]);

            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
    
                    // this would be your entity, i.e. SportMeetup
                    //$data = $event->getData();
    
                    // $sport = $data->getSport();
                    // $positions = null === $sport ? [] : $sport->getAvailablePositions();
    
                    // $form->add('position', ChoiceType::class, [
                    //     'class' => Position::class,
                    //     'placeholder' => '',
                    //     'choices' => $options['available_times'],
                    // ]);
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'appointment_types' => null,
            'available_times' => null
        ]);
    }

    private function test($appointmentTypeId)
    {
        
    }
}
