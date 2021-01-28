<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ArticleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Article', CKEditorType::class)
            ->add('Sauvegarder', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success text-right']
            ])
        ;
    }

}