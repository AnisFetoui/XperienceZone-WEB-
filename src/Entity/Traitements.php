<?php

namespace App\Entity;
use App\Repository\TraitementRepository;
use Doctrine\ORM\Mapping as ORM;


    #[ORM\Entity(repositoryClass:TraitementRepository::class)]
    class Traitements
    {
      
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $idt=null;
    
        #[ORM\Column]
        private ?int $refobj=null;
    
        #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
        private ?\DateTime $dater=null;
    
        #[ORM\ManyToOne(inversedBy:'Traitements')]
        private ?Utilisateur $Utilisateur=null;
       

        #[ORM\Column]
        private ?int $typer=null;
    
        
        #[ORM\Column( length: 500)]
        private ?string $resume=null;
    
      
        #[ORM\Column( length: 50)]
        private ?string $stat=null;
    
        #[ORM\ManyToOne(inversedBy:'Traitements')]
        private ?Réclamations $Réclamations=null;
      
    
    
    }