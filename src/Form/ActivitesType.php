<?php

namespace App\Form;

use App\Entity\Activites;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            
            ->add('lieuAct', ChoiceType::class, [
                'choices' => [
                    'ariana' => 'ariana',
                    'béja' => 'béja',
                    'ben arous' => 'ben arous',
                    'bizerte' => 'bizerte',
                    'gabès' => 'gabès',
                    'gafsa' => 'gafsa',
                    'jendouba' => 'jendouba',
                    'kairouan' => 'kairouan',
                    'kasserine' => 'kasserine',
                    'kébili' => 'kébili',
                    'kef' => 'kef',
                    'mahdia' => 'mahdia',
                    'manouba' => 'manouba',
                    'médenine' => 'médenine',
                    'monastir' => 'monastir',
                    'nabeul' => 'nabeul',
                    'sfax' => 'sfax',
                    'sidi bouzid' => 'sidi bouzid',
                    'siliana' => 'siliana',
                    'sousse' => 'sousse',
                    'tataouine' => 'tataouine',
                    'tozeur' => 'tozeur',
                    'tunis' => 'tunis',
                    'zaghouan' => 'zaghouan',
                
                

                ]
            ])
            ->add('adresse')
            ->add('images', FileType::class,[
                
                'mapped' => false,
                'required' => false,
            ])
            ->add('placeDispo')
            ->add('prixAct')
            ->add('duree')
            ->add('periode')
            //->add('user',null,['choice_label'=>'id_user',
            //'expanded' => True,
          //  'multiple' => True,
           // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activites::class,
        ]);
    }
}
