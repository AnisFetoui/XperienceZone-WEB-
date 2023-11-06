<?php

namespace App\Entity;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass:ProduitRepository::class)]
class Produit

{
 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProd=null;




 
    #[ORM\Column(length: 150)]
    private ?string  $nomProd=null;





    #[ORM\Column]
    private ?float $prixProd=null;



    #[ORM\Column(length: 150)]
    private ?string $descriptionProd=null;




    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $quantite=null;




    
    #[ORM\Column(length: 150)]
    private ?string $image=null;





   
    #[ORM\ManyToOne(inversedBy:'Produit')]
    private ?UCategorie $Categorie=null;


}