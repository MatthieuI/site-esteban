<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleModifierType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'data' => $options['title']
                ])
            ->add('abstract', TextAreaType::class, [
                'label' => 'Résumé',
                'data' => $options['abstract']
                ])
            ->add('htmlBody', CKEditorType::class, [
                'label' => 'Corps',
                'data' => $options['body']
                ])
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'title' => null,
            'body' => null,
            'abstract' => null
        ]);
    }

}