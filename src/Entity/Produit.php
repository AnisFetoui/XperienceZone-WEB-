<?php

namespace App\Entity;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Contraint as Assert;
use Doctrine\DBAL\Types\Types;
//use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_prod")]
    private ?int $idProd = null;

    #[Assert\NotBlank(message: 'Le champ "Nom" ne peut pas être vide.')]
    #[Assert\Length(max: 150, maxMessage: 'Le champ "Nom" ne peut pas dépasser {{ limit }} caractères.')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 150, maxMessage: 'Le champ "Nom" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length: 150)]
    private ?string $nomProd = null;


    #[Assert\NotBlank(message: 'Le champ "Prix" ne peut pas être vide.')]
    #[Assert\Type(type: 'float', message: 'Le champ "Prix" doit être un nombre décimal.')]

    #[Assert\NotBlank(message: 'Le champ "Prix" ne peut pas être vide.')]
    #[Assert\Type(type: 'float', message: 'Le champ "Prix" doit être un nombre décimal.')]
    #[ORM\Column]
    private ?float $prixProd = null;


    #[Assert\NotBlank(message: 'Le champ "Description" ne peut pas être vide.')]

    #[Assert\NotBlank(message: 'Le champ "Description" ne peut pas être vide.')]
    #[ORM\Column(length: 150)]
    private ?string $descriptionProd = null;

    #[Assert\NotBlank(message: 'Le champ "Quantité" ne peut pas être vide.')]
    #[Assert\Type(type: 'integer', message: 'Le champ "Quantité" doit être un nombre entier.')]
    #[Assert\NotBlank(message: 'Le champ "Quantité" ne peut pas être vide.')]
    #[Assert\Type(type: 'integer', message: 'Le champ "Quantité" doit être un nombre entier.')]
    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(length: 150)]
    private ?string $image = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: "Id_categorie", referencedColumnName: "Id_categorie")]
    private ?Categorie $categorie = null;
 
    #[ORM\Column]
    private ?float $noteProd = null;
   

    public function getIdProd(): ?int
    {
        return $this->idProd;
    }

    public function getNomProd(): ?string
    {
        return $this->nomProd;
    }

    public function setNomProd(string $nomProd): static
    {
        $this->nomProd = $nomProd;

        return $this;
    }

    public function getPrixProd(): ?float
    {
        return $this->prixProd;
    }

    public function setPrixProd(float $prixProd): static
    {
        $this->prixProd = $prixProd;

        return $this;
    }

    public function getDescriptionProd(): ?string
    {
        return $this->descriptionProd;
    }

    public function setDescriptionProd(string $descriptionProd): static
    {
        $this->descriptionProd = $descriptionProd;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    /**public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
       $this->categorie = $categorie;

        return $this;
    }*/
    

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getNoteProd(): ?float
    {
        return $this->noteProd;
    }

    public function setNoteProd(float $noteProd): static
    {
        $this->noteProd = $noteProd;

        return $this;
    }
}
