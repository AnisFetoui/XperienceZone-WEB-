<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProd')
            ->add('prixProd')
            ->add('descriptionProd')
            ->add('quantite')
            //->add('image')
            ->add('image', FileType::class, [
                'label' => 'Product Image', // Label du champ
                'mapped' => false,
                'required' => false, // Le champ n'est pas obligatoire
                
                
            ])
           // ->add('categorie')
           ->add('categorie', null, [
            'choice_label' => 'nomCategorie' // Spécifiez la propriété "nom" de l'objet Categorie comme représentation en chaîne
        ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
