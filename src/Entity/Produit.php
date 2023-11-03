<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="Id_categorie", columns={"Id_categorie"})})
 * @ORM\Entity
 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_prod", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idProd;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_prod", type="string", length=20, nullable=false)
     */
    private $nomProd;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_prod", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixProd;

    /**
     * @var string
     *
     * @ORM\Column(name="description_prod", type="string", length=100, nullable=false)
     */
    private $descriptionProd;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=false)
     */
    private $image;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_categorie", referencedColumnName="Id_categorie")
     * })
     */
    private $idCategorie;


}
