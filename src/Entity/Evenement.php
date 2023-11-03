<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_event", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_event", type="string", length=30, nullable=false)
     */
    private $nomEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="descript", type="string", length=100, nullable=false)
     */
    private $descript;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_event", type="date", nullable=false)
     */
    private $dateEvent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heure_event", type="time", nullable=false)
     */
    private $heureEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_event", type="string", length=20, nullable=false)
     */
    private $lieuEvent;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_participants", type="integer", nullable=false)
     */
    private $nbParticipants;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="organisateur", type="string", length=20, nullable=false)
     */
    private $organisateur;


}
