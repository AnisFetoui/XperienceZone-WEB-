<?php

namespace App\Entity;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ActivitesRepository::class)]
class Activites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
   #[ORM\Column]
    private ?int $idAct;

    #[ORM\Column( length: 50)]
    private ?string $nomAct;

    #[ORM\Column( length: 200)]
    private ?string $description;

    #[ORM\Column( length: 20)]
    private ?string $organisateur;

   #[ORM\Column]
    private ?int $lieuAct;

    #[ORM\Column( length: 50)]
    private ?string $adresse;

    #[ORM\Column( length: 600)]
    private ?string $images;

   #[ORM\Column]
    private ?int $placeDiso;

    #[ORM\Column(type: "string", length: 10)]
    private ?string $prixAct;

    #[ORM\Column]
    private ?int $duree;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $periode;

    #[ORM\Column]
    private ?int $idUser;

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

    public function getLieuAct(): ?int
    {
        return $this->lieuAct;
    }

    public function setLieuAct(int $lieuAct): static
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

    public function getPlaceDiso(): ?int
    {
        return $this->placeDiso;
    }

    public function setPlaceDiso(int $placeDiso): static
    {
        $this->placeDiso = $placeDiso;

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

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function setIdUser(int $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }
}
