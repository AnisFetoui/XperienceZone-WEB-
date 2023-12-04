<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    // ...

public function findProductByCriteria($nomProd, $prixProd)
{
    $queryBuilder = $this->createQueryBuilder('p');

    if ($nomProd) {
        $queryBuilder->andWhere('p.nomProd LIKE :nomProd')
            ->setParameter('nomProd', '%' . $nomProd . '%');
    }

    if ($prixProd) {
        $queryBuilder->andWhere('p.prixProd <= :prixProd')
            ->setParameter('prixProd', $prixProd);
    }

    return $queryBuilder->getQuery()->getResult();
}
public function findProduitByNom($searchString)
{
    return $this->createQueryBuilder('p')
        ->where('LOWER(p.nomProd) LIKE :search')
        ->setParameter('search', '%' . strtolower($searchString) . '%')
        ->getQuery()
        ->getResult();
}




//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
