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
    /**
     * @var int
     *
     * @ORM\Column(name="Id_ins", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idIns;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ins", type="date", nullable=false)
     */
    private $dateIns;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_ins", type="time", nullable=false)
     */
    private $heureIns;

    /**
     * @var int
     *
     * @ORM\Column(name="nbr_tickes", type="integer", nullable=false)
     */
    private $nbrTickes;

    /**
     * @var float
     *
     * @ORM\Column(name="frait_abonnement", type="float", precision=10, scale=0, nullable=false)
     */
    private $fraitAbonnement;

    /**
     * @var int|null
     *
     * @ORM\Column(name="activite_id", type="integer", nullable=true)
     */
    private $activiteId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;


}
