<?php

namespace App\Entity;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;



#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idMsg = null;

    #[ORM\Column(length: 150)]
    private ?string $contenuMsg = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heurEnvoiMsg = null;

    #[ORM\ManyToOne(inversedBy: 'Message')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'Message')]
    private ?Channel $channel = null;

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

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): static
    {
        $this->channel = $channel;

        return $this;
    }
}