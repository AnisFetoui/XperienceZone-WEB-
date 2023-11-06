<?php

namespace App\Entity;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass:MessageRepository::class)]
class Message
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idMsg=null;

   
    #[ORM\Column(length: 150)]
    private ?string  $contenuMsg=null;



       
   #[ORM\Column(type: Types:: DATETIME_MUTABLE, nullable:true)]
   private ?\DateTimeInterface $heurEnvoiMsg = null;

   #[ORM\ManyToOne(inversedBy:'Message')]
   private ?Utilisateur $Utilisateur=null;

  
    #[ORM\ManyToOne(inversedBy:'Message')]
    private ?Channel $Channel=null;
 
}
