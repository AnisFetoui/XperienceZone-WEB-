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
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;

class RegisterType extends AbstractType
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
                'attr' => ['class' => 'search__input form-control border-transparent', 
                'placeholder' => 'Password'],
            ],
            'second_options' => [
                'label' => 'Confirm Password',
                'attr' => ['class' => 'search__input form-control border-transparent', 
                'placeholder' => 'Confirm Password'],
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
            ->add("captcha", ReCaptchaType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Userr::class,
        ]);
    }
}
