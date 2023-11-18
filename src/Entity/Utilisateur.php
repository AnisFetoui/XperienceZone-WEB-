<?php

namespace App\Entity;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class Utilisateur
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ? int $idUser = null;


    #[Assert\NotBlank(message: 'Le champ "Nom d\'utilisateur" ne peut pas être vide.')]
    #[Assert\Length(max: 35, maxMessage: 'Le champ "Nom d\'utilisateur" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length:35)]
    private ?string $username = null;
    
    #[Assert\NotBlank(message: 'Le champ "Adresse email" ne peut pas être vide.')]
    #[Assert\Email(message: 'Veuillez entrer une adresse email valide.')]
    #[ORM\Column(length:50)]
    private ?string $mail = null;

    #[Assert\NotBlank(message: 'Le champ "Mot de passe" ne peut pas être vide.')]
    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit avoir au moins {{ limit }} caractères.')]
    #[ORM\Column(length:40)]
    private ?string $mdp = null;

    #[Assert\NotBlank(message: 'Le champ "Rôle" ne peut pas être vide.')]
    #[ORM\Column(length:15)]
    private ?string $role = null;

    #[Assert\NotBlank(message: 'Le champ "Image" ne peut pas être vide.')]
    #[ORM\Column(length:300 )]
    private ?string $image= null;

    
    #[Assert\NotBlank(message: 'Le champ "Âge" ne peut pas être vide.')]
    #[Assert\Type(type: 'integer', message: 'Le champ "Âge" doit être un nombre entier.')]
    #[ORM\Column]
    private ?int $age= null;
    
    #[Assert\NotBlank(message: 'Le champ "Sexe" ne peut pas être vide.')]
    #[ORM\Column(length:10 )]
    private ?string $sexe= null;
 
    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => true])]
    private ?bool $etat = true;
    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

}