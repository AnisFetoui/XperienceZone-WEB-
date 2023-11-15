<?php

namespace App\Entity;
use App\Repository\SentimentRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass:SentimentRepository::class)]
class Sentiment
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id")]
    private ?int $id=null;

   

    #[ORM\Column(length: 150)]
    private ?string $nom=null;

 

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name:"idCh", referencedColumnName:"idCh", nullable: false  )]
    private ?Channel $Channel=null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getChannel(): ?Channel
    {
        return $this->Channel;
    }

    public function setChannel(?Channel $Channel): static
    {
        $this->Channel = $Channel;

        return $this;
    }
 


}
