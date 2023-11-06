<?php

namespace App\Entity;
use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:PanierRepository::class)]
class Panier{

 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPanier=null;



    #[ORM\Column]
    private ?float $total=null;

 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $quantitePanier=null;

  
    #[ORM\ManyToOne(inversedBy:'Panier')]
    private ?Utilisateur $Utilisateur=null;





    #[ORM\ManyToOne(inversedBy:'Panier')]
    private ?Produit $Produit=null;
 

     }