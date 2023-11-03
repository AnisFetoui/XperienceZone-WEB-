<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sentiment
 *
 * @ORM\Table(name="sentiment", indexes={@ORM\Index(name="idCh", columns={"idCh"})})
 * @ORM\Entity
 */
class Sentiment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \Channel
     *
     * @ORM\ManyToOne(targetEntity="Channel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCh", referencedColumnName="idCh")
     * })
     */
    private $idch;


}
