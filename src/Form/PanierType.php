<?php

namespace App\Form;

use App\Entity\Panier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('total', HiddenType::class)
        ->add('quantitePanier')
       // ->add('utilisateur', null, [
         //   'choice_label' => 'username'
        //])
        ->add('produit', null, [
            'choice_label' => 'nomProd'
        ])
       
        ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getData();

            // Mettez à jour le total en fonction de la quantité et du prix du produit
            $produit = $data->getProduit();
            $prixProduit = $produit->getPrixProd();
            $quantitePanier = $data->getQuantitePanier();
            $total = $prixProduit * $quantitePanier;

            // Définissez la nouvelle valeur du champ "total"
            $data->setTotal($total);
        });
}




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
