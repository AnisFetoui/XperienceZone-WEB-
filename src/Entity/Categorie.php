<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="Id_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_categorie", type="string", length=50, nullable=false)
     */
    private $nomCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="description_categorie", type="string", length=100, nullable=false)
     */
    private $descriptionCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="type_categorie", type="string", length=30, nullable=false)
     */
    private $typeCategorie;


}
