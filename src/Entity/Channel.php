<?php

namespace App\Entity;
use App\Repository\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass:ChannelRepository::class)]
class Channel
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idCh=null;
   


    #[ORM\Column(length: 150)]
    private ?string $nomCh=null;



    #[ORM\ManyToOne(inversedBy:'Channel')]
    private ?Evenement $Evenement=null;

    public function getIdCh(): ?int
    {
        return $this->idCh;
    }

    public function getNomCh(): ?string
    {
        return $this->nomCh;
    }

    public function setNomCh(string $nomCh): static
    {
        $this->nomCh = $nomCh;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->Evenement;
    }

    public function setEvenement(?Evenement $Evenement): static
    {
        $this->Evenement = $Evenement;

        return $this;
    }

}
