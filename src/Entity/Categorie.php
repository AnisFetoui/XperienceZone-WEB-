<?php

namespace App\Entity;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass:CategorieRepository::class)]
class Categorie


{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCategorie=null;



   
    #[ORM\Column(length: 150)]
    private ?string $nomCategorie=null;




    #[ORM\Column(length: 150)]
    private ?string $descriptionCategorie=null;





    #[ORM\Column(length: 150)]
    private ?string $typeCategorie=null;


}