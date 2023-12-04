<?php
namespace App\Entity;
use App\Entity\Evenement;
use App\Repository\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\SerializerInterface;   
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChannelRepository::class)]
class Channel 
  {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idCh")]
    private ?int $idCh=null;
   

    #[Assert\NotBlank]
    #[Assert\Length( min: 3,  max: 20,  minMessage: 'Description must be at least {{ limit }} characters long',
    maxMessage: 'Description cannot be longer than {{ limit }} characters',)]
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
    }
    public function getIdEvent()
    {
        return $this->id_event;
    }


}
   

    

