<?php

namespace App\Form;

use App\Entity\Activites;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivitesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAct')
            ->add('description')
            ->add('organisateur')
            ->add('lieuAct')
            ->add('adresse')
            ->add('images')
            ->add('placeDiso')
            ->add('prixAct')
            ->add('duree')
            ->add('periode')
            ->add('idUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activites::class,
        ]);
    }
}
