<?php

namespace App\Entity;
use App\Repository\PanierRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPanier = null;

    
    #[Assert\NotBlank(message: 'Le champ "Total" ne peut pas être vide.')]
    #[Assert\Type(type: 'float', message: 'Le champ "Total" doit être un nombre décimal.')]
    #[ORM\Column]
    private ?float $total = null;


    #[Assert\NotBlank(message: 'Le champ "Quantité" ne peut pas être vide.')]
    #[Assert\Type(type: 'integer', message: 'Le champ "Quantité" doit être un nombre entier.')]
    #[ORM\Column]
    private ?int $quantitePanier = null;


    #[ORM\ManyToOne]
   #[ORM\JoinColumn(nullable: false, name: "id_user", referencedColumnName: "id_user")]
    private ?Userr $utilisateur = null;

    #[ORM\ManyToOne]
   #[ORM\JoinColumn(nullable: false, name: "id_prod", referencedColumnName: "id_prod")]
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

    public function getUtilisateur(): ?Userr
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Userr $utilisateur): self
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
