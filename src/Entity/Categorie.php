<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass:CategorieRepository::class)]
class Categorie


{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"Id_categorie")]
    private ?int $idCategorie=null;

    #[Assert\NotBlank(message: 'Le champ "Nom" ne peut pas être vide.')]
    #[Assert\Length(max: 150, maxMessage: 'Le champ "Nom" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length: 150)]
    private ?string $nomCategorie=null;

    #[Assert\NotBlank(message: 'Le champ "Description" ne peut pas être vide.')]
    #[Assert\Length(max: 150, maxMessage: 'Le champ "Description" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length: 150)]
    private ?string $descriptionCategorie=null;

    #[Assert\NotBlank(message: 'Le champ "Type" ne peut pas être vide.')]
    #[Assert\Length(max: 150, maxMessage: 'Le champ "Type" ne peut pas dépasser {{ limit }} caractères.')]
    #[ORM\Column(length: 150)]
    private ?string $typeCategorie=null;


    
    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }
    
    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): static
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }

    public function getDescriptionCategorie(): ?string
    {
        return $this->descriptionCategorie;
    }

    public function setDescriptionCategorie(string $descriptionCategorie): static
    {
        $this->descriptionCategorie = $descriptionCategorie;

        return $this;
    }

    public function getTypeCategorie(): ?string
    {
        return $this->typeCategorie;
    }

    public function setTypeCategorie(string $typeCategorie): static
    {
        $this->typeCategorie = $typeCategorie;

        return $this;
    }


}