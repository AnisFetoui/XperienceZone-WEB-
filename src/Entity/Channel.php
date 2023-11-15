<?php
namespace App\Entity;
use App\Entity\Evenement;
use App\Repository\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[ORM\Entity(repositoryClass: ChannelRepository::class)]
class Channel 
  {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idCh")]
    private ?int $idCh=null;
   
    #[ORM\Column(name: "nomCh", length: 150)]
    private ?string $nomCh=null;


   #[ORM\ManyToOne]
   #[ORM\JoinColumn(name:"id_event", referencedColumnName:"id_event", nullable: false  )]
   private ?Evenement $evenement=null;
  

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

        return $this;}

        public function getEvenement(): ?Evenement {
            return $this->evenement;
        }
        public function setEvenement(Evenement $evenement): self
        { 
             $this ->evenement = $evenement; 
             return $this;   
    }}
