<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;

#[ORM\Entity(repositoryClass:EvenementRepository::class)]
class Evenement
{
   #[ORM\Id]
   #[ORM\GenerateValue]
   #[ORM\Column]
   private ?int $idEvent=null;

   #[ORM\Column(length:150)]
   private ?string $nomEvent=null;

   #[ORM\Column(length:200)]
   private ?string $descript=null;

   
   #[ORM\Column(type: Types:: DATETIME_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $dateEvent = null;
  
     
   #[ORM\Column(type: Types:: DATETIME_MUTABLE, nullable:true)]
   private ?\DateTimeInterface $heureEvent = null;
    

    #[ORM\Column(length:200)]
   private ?string $lieuEvent=null;
   

   #[ORM\Column]
   private ?int $nbParticipants=null;
   

   #[ORM\Column(length:200)]
   private ?string $image=null;
  
   #[ORM\Column(length:200)]
   private ?string $organisateur=null;
  
   
  

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getNomEvent(): ?string
    {
        return $this->nomEvent;
    }

    public function setNomEvent(string $nomEvent): static
    {
        $this->nomEvent = $nomEvent;

        return $this;
    }

    public function getDescript(): ?string
    {
        return $this->descript;
    }

    public function setDescript(string $descript): static
    {
        $this->descript = $descript;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    public function getHeureEvent(): ?\DateTimeInterface
    {
        return $this->heureEvent;
    }

    public function setHeureEvent(\DateTimeInterface $heureEvent): static
    {
        $this->heureEvent = $heureEvent;

        return $this;
    }

    public function getLieuEvent(): ?string
    {
        return $this->lieuEvent;
    }

    public function setLieuEvent(string $lieuEvent): static
    {
        $this->lieuEvent = $lieuEvent;

        return $this;
    }

    public function getNbParticipants(): ?int
    {
        return $this->nbParticipants;
    }

    public function setNbParticipants(int $nbParticipants): static
    {
        $this->nbParticipants = $nbParticipants;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getOrganisateur(): ?string
    {
        return $this->organisateur;
    }

    public function setOrganisateur(string $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }


}
