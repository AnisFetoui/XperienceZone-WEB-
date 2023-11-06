<?php

namespace App\Entity;
use App\Repository\\UserRepository
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class Utilisateur
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ? int $idUser = null;

    #[ORM\Column(length:35)]
    private ?string $username = null;

    #[ORM\Column(length:50)]
    private ?string $mail = null;

    #[ORM\Column(length:40)]
    private ?string $mdp = null;

    #[ORM\Column(length:15)]
    private ?string $role = null;

    #[ORM\Column(length:300 )]
    private ?string $image= null;
    
    #[ORM\Column]
    private ?int $age= null;

    #[ORM\Column(length:10 )]
    private ?string $sexe= null;


    

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => true])]
    private ?bool $etat = true;

}
