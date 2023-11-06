<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass:TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GenerateValue]
    #[ORM\Column]
    private ?int $idTicket=null;
    

    #[ORM\Column]
    private ?int $numTicket=null;
    

    #[ORM\Column]
    private ?int $idUser=null;
    

    #[ORM\Column]
    private ?int $idEvent=null;
    

    #[ORM\Column(length:200)]
   private ?string $image=null;
   

   #[ORM\Column]
   private ?float $prix=null;
   

   #[ORM\Column(length:200)]
   private ?string $categorie=null;
  
   #[ORM\ManyToOne(inversedBy: 'tickets')]
   private ?Evenement $evenement=null;

    public function getIdTicket(): ?int
    {
        return $this->idTicket;
    }

    public function getNumTicket(): ?int
    {
        return $this->numTicket;
    }

    public function setNumTicket(int $numTicket): static
    {
        $this->numTicket = $numTicket;

        return $this;
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function setIdEvent(int $idEvent): static
    {
        $this->idEvent = $idEvent;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;

        return $this;
    }


}
