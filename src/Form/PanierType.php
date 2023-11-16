<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             ->add('total')
            ->add('quantitePanier')
            ->add('utilisateur', null, [
                'choice_label' => 'username' // Spécifiez la propriété "nom" de l'objet Categorie comme représentation en chaîne
            ])
            //->add('utilisateur')
            //->add('produit')
            ->add('produit', null, [
                'choice_label' => 'nomProd' // Spécifiez la propriété "nom" de l'objet Categorie comme représentation en chaîne
            ]);
    
        ;
    }





    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
