<?php

namespace App\Entity;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
USE Doctrine\DBAL\Types\Types;

use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
#[ORM\Table(name: "inscription")]
class Inscription

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $Id_ins;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateIns = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureIns = null;

    #[ORM\Column]
    private ?int $nbrTickes;

    #[ORM\Column]
    private ?float $fraitAbonnement;

    #[ORM\Column]
    private ?int $activiteId;


    #[ORM\Column]
    private ?int $userId;

    /*#[ORM\OneToMany(mappedBy: 'inscription', targetEntity: Activites::class)]
    #[ORM\JoinColumn(nullable : true)]
    private Collection $activitesliste;
    public function __construct()
    {
        $this->activitesliste = new ArrayCollection();
    }
        /**
     * @return Collection<int, Activites>
     */

    public function getIdIns(): ?int
    {
        return $this->Id_ins;
    }
   
    public function getDateIns(): ?\DateTimeInterface
    {
        return $this->dateIns;
    }

    public function setDateIns(?\DateTimeInterface $dateIns): static
    {
        $this->dateIns = $dateIns;

        return $this;
    }

    public function getHeureIns(): ?\DateTimeInterface
    {
        return $this->heureIns;
    }

    public function setHeureIns(?\DateTimeInterface $heureIns): static
    {
        $this->heureIns = $heureIns;

        return $this;
    }

    public function getNbrTickes(): ?int
    {
        return $this->nbrTickes;
    }

    public function setNbrTickes(int $nbrTickes): static
    {
        $this->nbrTickes = $nbrTickes;

        return $this;
    }

    public function getFraitAbonnement(): ?float
    {
        return $this->fraitAbonnement;
    }

    public function setFraitAbonnement(float $fraitAbonnement): static
    {
        $this->fraitAbonnement = $fraitAbonnement;

        return $this;
    }

    public function getActiviteId(): ?int
    {
        return $this->activiteId;
    }

    public function setActiviteId(int $activiteId): static
    {
        $this->activiteId = $activiteId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }
}
