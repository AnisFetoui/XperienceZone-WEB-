<?php

namespace App\Form;

use App\Entity\Traitements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TraitementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
             ->add('reclamations',null,[
            'choice_label' => 'idr'
             ])
            ->add('resume')
            ->add('stat', ChoiceType::class, [
                'choices' => [
                    'Valide' => 'Valide',
                    'Invalide' => 'Invalide',
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traitements::class,
        ]);
    }
}
