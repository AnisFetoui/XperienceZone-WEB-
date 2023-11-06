<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ActivitesRepository;

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
    private ?int $durée;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $periode;

    #[ORM\Column]
    private ?int $idUser;
}
