<?php

namespace App\Entity;
use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;



#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_prod")]
    private ?int $idProd = null;

    #[ORM\Column(length: 150)]
    private ?string $nomProd = null;

    #[ORM\Column]
    private ?float $prixProd = null;

    #[ORM\Column(length: 150)]
    private ?string $descriptionProd = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(length: 150)]
    private ?string $image = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name: "Id_categorie", referencedColumnName: "Id_categorie")]
    private ?Categorie $categorie = null;
 

   

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
}
