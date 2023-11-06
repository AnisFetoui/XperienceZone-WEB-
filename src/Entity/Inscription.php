<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inscription
 *
 * @ORM\Table(name="inscription")
 * @ORM\Entity
 */
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $Id_ins;

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
}
