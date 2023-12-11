<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numTicket')
         /* ->add('image',FileType::class,[
                'label'=> 'ticket image',
                'mapped'=> false,
                'required'=> false ])*/
    
         //   ->add('prix', NumberType::class, [
              //  'label' => 'Prix ,
              //  'scale' => 2, // Précision décimale (2 chiffres après la virgule)
                // Autres options du champ NumberType
           // ])
           /*->add('prix', MoneyType::class, [
            'label' => 'Prix',
            'currency' => 'TND', // Code de la devise (Tunisian Dinar)
           
        ])*/
            ->add('categorie', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Normal' => 'normal',
                    'VIP' => 'vip',
                    'Étudiant' => 'etudiant',
                    'Gratuit' => 'gratuit',
                ],
             
            ])
           // ->add('evenement',null,['choice_label'=> 'nomEvent'])
           // ->add('user',null,['choice_label'=> 'username'])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}