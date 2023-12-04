<?php

namespace App\Repository;

use App\Entity\Reclamations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamations>
 *
 * @method Reclamations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamations[]    findAll()
 * @method Reclamations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamations::class);
    }

//    /**
//     * @return Reclamations[] Returns an array of Reclamations objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reclamations
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findReclamationsByString($searchString)
{
    return $this->createQueryBuilder('r')
        ->where('r.details LIKE :search')
        ->setParameter('search', '%' . $searchString . '%')
        ->getQuery()
        ->getResult();
}

public function getCountByType(): array
{
    return $this->createQueryBuilder('r')
        ->select('r.typerec, COUNT(r.idr) as count')
        ->groupBy('r.typerec')
        ->getQuery()
        ->getResult();
}

public function getCountByTypeAndMonth(): array
{
    $reclamations = $this->createQueryBuilder('r')
        ->select('r.typerec, r.daterec')
        ->andWhere('r.daterec BETWEEN :start AND :end')
        ->setParameter('start', new \DateTime('2023-01-01')) // Date de début pour l'année 2023
        ->setParameter('end', new \DateTime('2023-12-31')) // Date de fin pour l'année 2023
        ->getQuery()
        ->getResult();

    $stats = [];
    foreach ($reclamations as $reclamation) {
        $month = $reclamation['daterec']->format('n'); // Extraction du mois (1 à 12)
        $type = $reclamation['typerec'];

        $stats[] = [
            'typerec' => $type,
            'mois' => $month
        ];
    }

    return $stats;
}


}
