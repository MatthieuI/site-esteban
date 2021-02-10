<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ChangePasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
            ->add('password2', TextType::class, ['label' => 'Confimation du mot de passe'])
            ->add('valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success']
        ]);
    }
}
