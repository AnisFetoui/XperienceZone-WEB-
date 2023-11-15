<?php

namespace App\Form;

use App\Entity\Traitements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraitementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refobj')
            ->add('dater')
            ->add('typer')
            ->add('resume')
            ->add('stat')
            ->add('Utilisateur')
            ->add('reclamations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traitements::class,
        ]);
    }
}
