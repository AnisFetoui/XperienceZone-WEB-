<?php

namespace App\Entity;

use App\Entity\Evenement;
use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\UserInterface;



#[ORM\Entity(repositoryClass:TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue] 
    #[ORM\Column(name:"id_ticket")]
    private ?int $idTicket=null;
    

    #[ORM\Column]
    private ?int $numTicket=null;


    #[ORM\Column(length:200)]
   private ?string $image=null;
   

   #[ORM\Column]
   private ?float $prix=null;
   

   #[ORM\Column(length:200)]
   private ?string $categorie=null;

  
  // #[ORM\ManyToOne(inversedBy: 'tickets')]
  // #[ORM\ManyToOne]
  // #[ORM\JoinColumn(nullable: false, name: "idEvent", referencedColumnName: "idEvent")]
  // private ?Evenement $evenement=null;
  #[ORM\ManyToOne]
  #[ORM\JoinColumn(nullable: false, name: "id_event", referencedColumnName: "id_event")]
  private ?Evenement $evenement = null;

  // #[ORM\ManyToOne(inversedBy: 'tickets')]
   #[ORM\ManyToOne]
   #[ORM\JoinColumn(nullable: false, name: "id_user", referencedColumnName: "id_user")]
   private ?Userr $user=null;


   

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

   public function getUserticket(): ?Userr
   {
       return $this->user;
   }

   public function setUserticket(?Userr $user): static
   {
       $this->user = $user;

       return $this;
   }
   public function getUser(): ?UserInterface
   {
       return $this->user;
   }

   public function setUser(?UserInterface $user): static
   {
       $this->user = $user;

       return $this;
   }



















    
}
