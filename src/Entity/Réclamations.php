<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;


    #[ORM\Entity(repositoryClass:ReclamationRepository::class)]
    class Réclamations
    {
      
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $idr=null;
    
        #[ORM\ManyToOne(inversedBy:'Réclamations')]
        private ?Utilisateur $Utilisateur=null;
       

        #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
        private ?\DateTime $daterec = null;
    
       
        #[ORM\Column]
        private ?int $typerec=null;
    
        #[ORM\Column]
        private ?int $refobject=null;
         }