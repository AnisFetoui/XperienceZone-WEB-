<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Traitements
 *
 * @ORM\Table(name="traitements", indexes={@ORM\Index(name="idrec", columns={"idrec"})})
 * @ORM\Entity
 */
class Traitements
{
    /**
     * @var int
     *
     * @ORM\Column(name="idT", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idt;

    /**
     * @var int
     *
     * @ORM\Column(name="refobj", type="integer", nullable=false)
     */
    private $refobj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateR", type="date", nullable=false)
     */
    private $dater;

    /**
     * @var int
     *
     * @ORM\Column(name="idU", type="integer", nullable=false)
     */
    private $idu;

    /**
     * @var int
     *
     * @ORM\Column(name="typeR", type="integer", nullable=false)
     */
    private $typer;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text", length=65535, nullable=false)
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="stat", type="string", length=50, nullable=false)
     */
    private $stat;

    /**
     * @var \Réclamations
     *
     * @ORM\ManyToOne(targetEntity="Réclamations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idrec", referencedColumnName="idR")
     * })
     */
    private $idrec;


}
