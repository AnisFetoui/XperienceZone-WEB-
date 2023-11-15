<?php

namespace App\Entity;
use App\Repository\TraitementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;


    #[ORM\Entity(repositoryClass:TraitementRepository::class)]
    class Traitements
    {
      
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $idt=null;
    
        #[ORM\Column]
        private ?int $refobj=null;
    
        #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
        private ?\DateTime $dater=null;
    
        #[ORM\ManyToOne(inversedBy:'Traitements')]
        private ?Utilisateur $Utilisateur=null;
       

        #[ORM\Column]
        private ?int $typer=null;
    
        
        #[ORM\Column( length: 500)]
        private ?string $resume=null;
    
      
        #[ORM\Column( length: 50)]
        private ?string $stat=null;
    
        #[ORM\ManyToOne(targetEntity: Reclamations::class,inversedBy:'traitements')]
        private ?Reclamations $reclamations=null;

        public function getIdt(): ?int
        {
            return $this->idt;
        }

        public function getRefobj(): ?int
        {
            return $this->refobj;
        }

        public function setRefobj(int $refobj): static
        {
            $this->refobj = $refobj;

            return $this;
        }

        public function getDater(): ?\DateTimeInterface
        {
            return $this->dater;
        }

        public function setDater(?\DateTimeInterface $dater): static
        {
            $this->dater = $dater;

            return $this;
        }

        public function getTyper(): ?int
        {
            return $this->typer;
        }

        public function setTyper(int $typer): static
        {
            $this->typer = $typer;

            return $this;
        }

        public function getResume(): ?string
        {
            return $this->resume;
        }

        public function setResume(string $resume): static
        {
            $this->resume = $resume;

            return $this;
        }

        public function getStat(): ?string
        {
            return $this->stat;
        }

        public function setStat(string $stat): static
        {
            $this->stat = $stat;

            return $this;
        }

        public function getUtilisateur(): ?Utilisateur
        {
            return $this->Utilisateur;
        }

        public function setUtilisateur(?Utilisateur $Utilisateur): static
        {
            $this->Utilisateur = $Utilisateur;

            return $this;
        }

        public function getReclamations(): ?Reclamations
        {
            return $this->reclamations;
        }

        public function setReclamations(?Reclamations $reclamations): static
        {
            $this->reclamations = $reclamations;

            return $this;
        }
      
    
    
    }