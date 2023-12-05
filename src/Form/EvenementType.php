<?php

namespace App\Form;
use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;
class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEvent', null, [
        'attr' => [
            'class' => 'search__input form-control border-transparent',
            'placeholder' => 'Event name'
        ],
    ])
    ->add('descript', null, [
        'attr' => [
            'class' => 'search__input form-control border-transparent',
            'placeholder' => 'Event description'
        ],
    ])
    ->add('dateEvent')
    ->add('heureEvent', null, [
        'attr' => [
            'class' => 'search__input form-control border-transparent',
            'placeholder' => 'Event time'
        ],
    ])
    ->add('lieuEvent', ChoiceType::class, [
        'label' => 'Event location',
        'choices' => [
            'Ariana' => 'Ariana',
            'Béja' => 'Béja',
            'Ben Arous' => 'Ben Arous',
            'Bizerte' => 'Bizerte',
            'Gabès' => 'Gabès',
            'Gafsa' => 'Gafsa',
            'Jendouba' => 'Jendouba',
            'Kairouan' => 'Kairouan',
            'Kasserine' => 'Kasserine',
            'Kébili' => 'Kébili',
            'Le Kef' => 'Le Kef',
            'Mahdia' => 'Mahdia',
            'La Manouba' => 'La Manouba',
            'Médenine' => 'Médenine',
            'Monastir' => 'Monastir',
            'Nabeul' => 'Nabeul',
            'Sfax' => 'Sfax',
            'Sidi Bouzid' => 'Sidi Bouzid',
            'Siliana' => 'Siliana',
            'Sousse' => 'Sousse',
            'Tataouine' => 'Tataouine',
            'Tozeur' => 'Tozeur',
            'Tunis' => 'Tunis',
            'Zaghouan' => 'Zaghouan',
           
        ],
        'attr' => [
            'class' => 'form-select box mt-3 sm:mt-0',
            'placeholder' => 'Select a place'
        ],
    ])
    ->add('nbParticipants', null, [
        'attr' => [
            'class' => 'search__input form-control border-transparent',
            'placeholder' => 'Number of participants'
        ],
    ])
    ->add('image', FileType::class, [
        'label' => 'Event image',
        'mapped' => false,
        'required' => false,
        'constraints' => [
            new File([
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                ],
                'mimeTypesMessage' => 'Please upload a valid image file (JPG, PNG).',
            ])
        ]
    ])
    ->add('organisateur', null, [
        'attr' => [
            'class' => 'search__input form-control border-transparent',
            'placeholder' => 'Event organizer'
        ],
    ])

    ->add("recaptcha", ReCaptchaType::class);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }

}