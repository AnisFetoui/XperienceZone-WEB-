<?php

namespace App\Form;

use App\Entity\Userr;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;


class UserrType extends AbstractType
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
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Password',
                'attr' => [
                    'class' => 'search__input form-control border-transparent',
                    'placeholder' => 'Password',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le champ "Mot de passe" ne peut pas être vide.']),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit avoir au moins {{ limit }} caractères.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9a-zA-Z]*$/',
                        'message' => 'Le mot de passe doit contenir uniquement des chiffres et des lettres.',
                    ]),
                ],
            ],
            'second_options' => [
                'label' => 'Confirm Password',
                'attr' => [
                    'class' => 'search__input form-control border-transparent',
                    'placeholder' => 'Confirm Password',
                ],
            ],
        ])
            ->add('age', null, [
                'attr' => [
                    'class' => 'search__input form-control border-transparent', 
                    'placeholder' => 'age'
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
            ->add('etat')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Userr::class,
        ]);
    }
}
