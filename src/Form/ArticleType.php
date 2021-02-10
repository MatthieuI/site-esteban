<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ArticleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('htmlBody', CKEditorType::class, ['label' => 'Corps'])
            ->add('Preview', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Prévisualiser'
            ])
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success'],
                'label' => 'Publier'
            ])
        ;
    }

}