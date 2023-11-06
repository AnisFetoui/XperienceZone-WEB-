<?php

namespace App\Entity;
use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPanier = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?int $quantitePanier = null;

    #[ORM\ManyToOne(inversedBy: 'panier')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'panier')]
    private ?Produit $produit = null;

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getQuantitePanier(): ?int
    {
        return $this->quantitePanier;
    }

    public function setQuantitePanier(int $quantitePanier): static
    {
        $this->quantitePanier = $quantitePanier;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}