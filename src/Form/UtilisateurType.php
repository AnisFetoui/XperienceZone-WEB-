<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;




class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('username', null, [
            'attr' => [
                'class' => 'search__input form-control border-transparent', 
                'placeholder' => 'Username'
            ],
        ])
        ->add('mail', null, [
            'attr' => [
                'class' => 'search__input form-control border-transparent', 
                'placeholder' => 'mail'
            ],
        ])
        ->add('mdp', null, [
            'attr' => [
                'class' => 'search__input form-control border-transparent', 
                'placeholder' => 'mdp'
            ],
        ])
        ->add('age', null, [
            'attr' => [
                'class' => 'search__input form-control border-transparent', 
                'placeholder' => 'age'
            ],
        ])
       ->add('role', ChoiceType::class, [
            'choices' => [
                'Client' => 'Client',
                'Manager' => 'Manager',
                
            ],
            
            'attr' => [
                'class' => 'class="form-select box mt-3 sm:mt-0 "', 
                'placeholder' => 'Select a role'
            ],
            ])

            
       
      
        ->add('sexe', ChoiceType::class, [
            'choices' => [
                'Male' => 'male',
                'Female' => 'female',
            ],
        'expanded' => True,
        'multiple' => False,
        
        
        ])
        ->add('image', FileType::class, [
            'label' => 'Choose a file',
            'required' => true,
            'constraints' => [
                new File([
                    'mimeTypes' => [
                        'image/jpg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPG, PNG).',
                ])
                ],
                'data_class' => null,
        ])
            ->add('etat')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
