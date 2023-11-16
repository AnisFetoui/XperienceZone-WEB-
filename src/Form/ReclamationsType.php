<?php

namespace App\Form;

use App\Entity\Reclamations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class ReclamationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('utilisateur', null, [ 
            'choice_label' => 'mail'
            ])
            ->add('daterec', null)
            ->add('typerec', ChoiceType::class, [
                'choices' => [
                    'Réclamation liée à une activité' => 3,
                    'Réclamation liée à un évènement' => 2,
                    'Réclamation liée à un produit' => 1,
                ],
                'placeholder' => 'Sélectionnez le type de réclamation', // Optionnel : affiche un texte vide par défaut
                'attr' => ['id' => 'reclamations_typerec'],
                /* 'constraints' => [
                    new Assert\NotBlank(),
                ], */
            ])
          
            ->add('refobject'/* , IntegerType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric', 'message' => 'Ce champ doit être un nombre.']),
                ],
                
            ] */)     
            ->add('details', TextareaType::class/* , [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ] */);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamations::class,
        ]);
    }
}

