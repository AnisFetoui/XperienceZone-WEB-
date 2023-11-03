<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message", indexes={@ORM\Index(name="idCh", columns={"idCh"}), @ORM\Index(name="fk_id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="idMsg", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idmsg;

    /**
     * @var string
     *
     * @ORM\Column(name="contenuMsg", type="string", length=255, nullable=false)
     */
    private $contenumsg;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="heurEnvoiMsg", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $heurenvoimsg = 'CURRENT_TIMESTAMP';

    /**
     * @var \Channel
     *
     * @ORM\ManyToOne(targetEntity="Channel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCh", referencedColumnName="idCh")
     * })
     */
    private $idch;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;


}
