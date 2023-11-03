<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activites
 *
 * @ORM\Table(name="activites")
 * @ORM\Entity
 */
class Activites
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_act", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAct;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_act", type="string", length=50, nullable=false)
     */
    private $nomAct;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=200, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="organisateur", type="string", length=20, nullable=false)
     */
    private $organisateur;

    /**
     * @var int
     *
     * @ORM\Column(name="lieu_act", type="integer", nullable=false)
     */
    private $lieuAct;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="images", type="string", length=100, nullable=false)
     */
    private $images;

    /**
     * @var int
     *
     * @ORM\Column(name="place_diso", type="integer", nullable=false)
     */
    private $placeDiso;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_act", type="string", length=10, nullable=false)
     */
    private $prixAct;

    /**
     * @var int
     *
     * @ORM\Column(name="durée", type="integer", nullable=false)
     */
    private $durée;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="string", length=50, nullable=false)
     */
    private $periode;


}
