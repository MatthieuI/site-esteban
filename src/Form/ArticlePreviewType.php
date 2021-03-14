<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticlePreviewType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['style' => "display:none;"],
                'data' => $options['title'],
                'attr' => ['style' => "display:none;"]
                ])
            ->add('abstract', TextAreaType::class, [
                'label' => 'Résumé',
                'label_attr' => ['style' => "display:none;"],
                'data' => $options['abstract'],
                'attr' => ['style' => "display:none;"]
                ])
            ->add('htmlBody', CKEditorType::class, [
                'label' => 'Corps',
                'label_attr' => ['style' => "display:none;"],
                'data' => $options['body'],
                'attr' => ['style' => "display:none;"]
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