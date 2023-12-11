<?php

namespace App\Entity;
use App\Entity\Channel;
use App\Entity\Userr;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message extends AbstractController
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idMsg")]
    private ?int $idMsg = null;


      
    #[Assert\NotBlank]
    #[Assert\Length( min: 2,  max: 256,  minMessage: 'Description must be at least {{ limit }} characters long',
        maxMessage: 'Description cannot be longer than {{ limit }} characters',)]
    #[ORM\Column(name: "contenuMsg", length: 150)]
    private ?string $contenuMsg = null;

   
   #[ORM\Column(name: "heurEnvoiMsg")] 
   private ?\DateTime $heurEnvoiMsg = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "idCh", referencedColumnName: "idCh", nullable: false)]
    private ?Channel $channel = null;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id_user", nullable: false)]
    private ?Userr $utilisateur = null;


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

    public function getUtilisateur(): ?Userr
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Userr $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->utilisateur;
    }

    public function setUser(?UserInterface $utilisateur): static
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