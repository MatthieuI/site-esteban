<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MessageType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class)
            ->add('Adresse_mail', EmailType::class)
            ->add('Sujet', TextType::class)
            ->add('Message', TextareaType::class, [
                'attr' => ['rows' => 5]
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => 'btn btn-dark']
            ])
        ;
    }

}