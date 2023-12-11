<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idR")]
    private ?int $idr = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: "idU", referencedColumnName: "id_user")]
    private ?Userr $utilisateur = null;

 /* #[Assert\Range(
        min = "2023-01-01",
        max = "2023-12-31",
        notInRangeMessage = "La date doit être en 2023."
    )] */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date de reclamation doit être remplie ")]
    #[Assert\Range(
        min: "2023-01-01",
        max: "2023-12-31",
        notInRangeMessage: "La date doit être en 2023"
    )]
    private ?\DateTime $daterec = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le type de reclamation doit être rempli")]
    private ?int $typerec = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La reference doit être remplie")]
    #[Assert\Type(type: 'numeric', message: "La reference doit être composé uniquement de chiffres")]
    private ?int $refobject = null;

    #[ORM\Column( length: 500)]
    #[Assert\NotBlank(message: "Les details doivent être remplie")]
    private ?string $details=null;



    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getDaterec(): ?\DateTimeInterface
    {
        return $this->daterec;
    }

    public function setDaterec(?\DateTimeInterface $daterec): static
    {
        $this->daterec = $daterec;

        return $this;
    }

    public function getTyperec(): ?int
    {
        return $this->typerec;
    }

    public function setTyperec(int $typerec): static
    {
        $this->typerec = $typerec;

        return $this;
    }

    public function getRefobject(): ?int
    {
        return $this->refobject;
    }

    public function setRefobject(int $refobject): static
    {
        $this->refobject = $refobject;

        return $this;
    }

    public function getUtilisateur(): ?Userr
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Userr $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    
    public function getUser(): ?UserInterface
    {
        return $this->utilisateur;
    }

    public function setUser(?UserInterface $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }


    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }
}
