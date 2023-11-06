<?php

namespace App\Entity;
use App\Repository\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;


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

}
