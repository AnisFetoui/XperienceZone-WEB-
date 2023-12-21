<?php

namespace App\Entity;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ActivitesRepository::class)]
class Activites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]

    private ?int $idAct;
    
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide" )]
    
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: "name can't contain numbers or special caracters"
        )]
    #[ORM\Column( length: 50)]
    private ?string $nomAct;



    #[ORM\Column( length: 200)]
    private ?string $description;


    #[Assert\NotBlank(message: "add organiser name")]
    
    #[ORM\Column( length: 20)]
    private ?string $organisateur;


   #[ORM\Column(type: "string", length: 10)]
    private ?string $lieuAct;

    #[Assert\NotBlank(message: "add addres")]
    #[ORM\Column( length: 50)]
    private ?string $adresse;
    
  
    #[ORM\Column( length: 600)]
    private ?string $images;
    
    #[Assert\NotBlank(message: "add min participant")]
   #[ORM\Column]
    private ?int $placeDispo;
  
    #[Assert\NotBlank(message: "add a price")]
    #[Assert\Regex(
        pattern: '/^\d+\.\d{2}$/',
        message: 'Price should have the format "$$.$$" (like: "20.00")'
    )]
    #[ORM\Column(type: "string", length: 10)]
    private ?string $prixAct;

    #[Assert\NotBlank(message: "add duration")]
    #[ORM\Column]
    private ?int $duree;

    #[Assert\NotBlank(message: "add a periode for your activity")]
    #[Assert\Regex(
        pattern: '/^\d{2}\/\d{2}\/\d{4} - \d{2}\/\d{2}\/\d{4}$/',
        message: 'Periode should have the format "DD/MM/YYYY - DD/MM/YYYY"'
    )]
    #[ORM\Column(type: "string", length: 50)]
    private ?string $periode;
  

    #[ORM\Column]
    private ?int $idUser;

    
    
    #[ORM\OneToOne]
   #[ORM\JoinColumn(nullable: false, name: "id_user", referencedColumnName: "id_user")]
   private ?Userr $user=null;

    public function getIdAct(): ?int
    {
        return $this->idAct;
    }

    public function getNomAct(): ?string
    {
        return $this->nomAct;
    }

    public function setNomAct(string $nomAct): static
    {
        $this->nomAct = $nomAct;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getLieuAct(): ?string
    {
        return $this->lieuAct;
    }

    public function setLieuAct(string $lieuAct): static
    {
        $this->lieuAct = $lieuAct;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getImages(): ?string
    {
        return $this->images;
    }

    public function setImages(string $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getPlaceDispo(): ?int
    {
        return $this->placeDispo;
    }

    public function setPlaceDispo(int $placeDispo): static
    {
        $this->placeDispo = $placeDispo;

        return $this;
    }

    public function getPrixAct(): ?string
    {
        return $this->prixAct;
    }

    public function setPrixAct(string $prixAct): static
    {
        $this->prixAct = $prixAct;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): static
    {
        $this->periode = $periode;

        return $this;
    }


    public function getUser(): ?Userr
    {
        return $this->user;
    }
 
    public function setUser(?Userr $user): static
    {
        $this->user = $user;
 
        return $this;
    }

    public function getUserr(): ?UserInterface
    {
        return $this->user;
    }

    public function setUserr(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }
}
