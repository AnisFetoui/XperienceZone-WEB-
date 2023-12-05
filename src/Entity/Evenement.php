<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EvenementRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass:EvenementRepository::class)]
class Evenement
{
   #[ORM\Id]
   #[ORM\GeneratedValue]
   #[ORM\Column]
   private ?int $idEvent=null;





   #[Assert\NotBlank(message: "Le nom de l'événement ne peut pas être vide")]
   #[Assert\Length(
     max: 255,
     maxMessage: 'Le nom de l\'événement ne peut pas dépasser {{ limit }} caractères.'
 )]
   #[ORM\Column(length:150)]
  private ?string $nomEvent=null;
 
  
  #[Assert\NotBlank(message: "La description de l'événement ne peut pas être vide")]
   #[ORM\Column(length:200)]
 
   private ?string $descript=null;

   #[ORM\Column(type: Types:: DATETIME_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $dateEvent = null;
  
    #[Assert\NotBlank(message: "L'heure de l'événement ne peut pas être vide")]
    #[ORM\Column(length:200)]
    private ?string $heureEvent=null;
    
    #[Assert\NotBlank(message: "Le lieu de l'événement ne peut pas être vide")]
    #[ORM\Column(length:200)]
   private ?string $lieuEvent=null;
   
   #[Assert\NotBlank(message: "Le nombre de participants ne peut pas être vide")]
   #[ORM\Column]
   private ?int $nbParticipants=null;
   
   #[Assert\Image(
    maxSize: '5M', // Maximum file size (adjust as needed)
    maxSizeMessage: 'The image file is too large. Max size: {{ limit }}',
    mimeTypes: ['image/jpeg', 'image/png'], // Allowed MIME types
    mimeTypesMessage: 'Please upload a valid image (JPEG or PNG).',
)]
   #[ORM\Column(length:200)]
   private ?string $image=null;
  
   #[Assert\NotBlank(message: "L'organisateur de l'événement ne peut pas être vide")]
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

   public function setDateEvent(?\DateTimeInterface $dateEvent): static
   {
       $this->dateEvent = $dateEvent;

       return $this;
   }

   public function getHeureEvent(): ?string
   {
       return $this->heureEvent;
   }

   public function setHeureEvent(string $heureEvent): static
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

   public function setImage(string $image): self
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