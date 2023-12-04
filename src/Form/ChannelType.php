<?php

namespace App\Form;

use App\Entity\Channel;
use Proxies\__CG__\App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;

class ChannelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nomCh', null, [
              'constraints' => [
               new NotBlank([
               'message' => 'Le champ "name channel " field cannot be empty.',
                ]),
            ],
        ])
        ->add('evenement',EntityType::class,[ 'class' => Evenement::class,  'choice_label' => 'nomEvent',  
        'attr' => [
            'style' => 'margin-top: 15px;', 
        ],                   
        ])  ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Channel::class,
        ]);
    }
}
