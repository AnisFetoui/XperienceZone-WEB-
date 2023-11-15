<?php

namespace App\Entity;
use App\Entity\Channel;
use App\Entity\Utilisateur;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;




#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idMsg")]
    private ?int $idMsg = null;

    #[ORM\Column(name: "contenuMsg", length: 150)]
    private ?string $contenuMsg = null;

   
    
   // #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
   #[ORM\Column(name: "heurEnvoiMsg")] 
   private ?\DateTimeInterface $heurEnvoiMsg = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "idCh", referencedColumnName: "idCh", nullable: false)]
    private ?Channel $channel = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user", nullable: false)]
    private ?Utilisateur $utilisateur = null;


    public function getIdMsg(): ?int
    {
        return $this->idMsg;
    }

    public function getContenuMsg(): ?string
    {
        return $this->contenuMsg;
    }

    public function setContenuMsg(string $contenuMsg): static
    {
        $this->contenuMsg = $contenuMsg;

        return $this;
    }

    public function getHeurEnvoiMsg(): ?\DateTimeInterface
    {
        return $this->heurEnvoiMsg;
    }

    public function setHeurEnvoiMsg(?\DateTimeInterface $heurEnvoiMsg): static
    {
        $this->heurEnvoiMsg = $heurEnvoiMsg;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(Channel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

}