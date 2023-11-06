<?php

namespace App\Entity;
use App\Repository\SentimentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sentiment
 *
 * @ORM\Table(name="sentiment", indexes={@ORM\Index(name="idCh", columns={"idCh"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:SentimentRepository::class)]
class Sentiment
{
  
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id=null;

   

    #[ORM\Column(length: 150)]
    private ?string $nom=null;

 
    #[ORM\ManyToOne(inversedBy:'Sentiment')]
    private ?Channel $Channel=null;
 


}