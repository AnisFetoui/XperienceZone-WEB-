<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Réclamations
 *
 * @ORM\Table(name="réclamations")
 * @ORM\Entity
 */
class Réclamations
{
    /**
     * @var int
     *
     * @ORM\Column(name="idR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, nullable=false)
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateINC", type="date", nullable=false)
     */
    private $dateinc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateREC", type="date", nullable=false)
     */
    private $daterec;

    /**
     * @var int
     *
     * @ORM\Column(name="typeRec", type="integer", nullable=false)
     */
    private $typerec;

    /**
     * @var int
     *
     * @ORM\Column(name="refObject", type="integer", nullable=false)
     */
    private $refobject;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=false)
     */
    private $details;


}
