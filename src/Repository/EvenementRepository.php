<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

/*public function search(?string $query, ?string $location): array
{
    $qb = $this->createQueryBuilder('e');

    // Ajouter des conditions de recherche en fonction des paramètres
    if ($query) {
        $qb->andWhere('e.nom_event LIKE :query')
            ->setParameter('query', '%' . $query . '%');
    }

    if ($location) {
        $qb->andWhere('e.lieu_event LIKE :location')
            ->setParameter('location', '%' . $location . '%');
    }

    // Exécutez la requête et retournez les résultats
    return $qb->getQuery()->getResult();
}


*/

public function findByNomAndLieu($searchQuery)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nomEvent LIKE :query OR e.lieuEvent LIKE :query')
            ->setParameter('query', '%' . $searchQuery . '%')
            ->getQuery()
            ->getResult();
    }

}
