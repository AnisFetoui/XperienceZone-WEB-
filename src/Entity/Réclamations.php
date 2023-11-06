<?php

namespace App\Entity;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Réclamations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idr = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $daterec = null;

    #[ORM\Column]
    private ?int $typerec = null;

    #[ORM\Column]
    private ?int $refobject = null;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}