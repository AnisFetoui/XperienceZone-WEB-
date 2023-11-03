<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Channel
 *
 * @ORM\Table(name="channel", indexes={@ORM\Index(name="id_event", columns={"id_event"})})
 * @ORM\Entity
 */
class Channel
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCh", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idch;

    /**
     * @var string
     *
     * @ORM\Column(name="nomCh", type="string", length=11, nullable=false)
     */
    private $nomch;

    /**
     * @var \Evenement
     *
     * @ORM\ManyToOne(targetEntity="Evenement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_event", referencedColumnName="id_event")
     * })
     */
    private $idEvent;


}
