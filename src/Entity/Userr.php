<?php

namespace App\Entity;

use App\Repository\UserrRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserrRepository::class)]
class Userr implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ? int $idUser = null;

    #[Assert\NotBlank(message: '  Le champ "username" ne peut pas être vide.')]
    #[Assert\Length(max: 35, maxMessage: 'Le champ "Nom d\'utilisateur" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length:35)]
    private ?string $username = null;

    #[Assert\NotBlank(message: 'Le champ "Email" ne peut pas être vide.')]
    #[Assert\Email(message: 'Veuillez entrer une adresse email valide.')]
    #[ORM\Column(length: 50)]
    private ?string $mail = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(message: 'Le champ "Mot de passe" ne peut pas être vide.')]
    #[Assert\Length(min: 6, minMessage: 'Le mot de passe doit avoir au moins {{ limit }} caractères.')]
    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern:"/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        message: 'Your password must be Strength',
    )]    private ?string $password = null;



    #[ORM\Column(length: 150)]
    private ?string $reset_token = null;

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

    

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsernamee(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
       // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

 /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

     /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getSalt(): ?string
    {
        // The salt is deprecated and not needed with modern password hashing methods
        return null;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
/*
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }*/

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


    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->reset_token;
    }

    /**
     * @param mixed $reset_token
     */
    public function setResetToken($reset_token): void
    {
        $this->reset_token = $reset_token;
    }

    public function getUserDataForQrCode(): string
    {
        $data = "Username: {$this->username}, Email: {$this->mail}, Age: {$this->age}, Gender: {$this->sexe}, Etat: {$this->etat}";
    
        return $data;
    }
}
