<?php

namespace App\Repository;

use App\Entity\Sentiment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sentiment>
 *
 * @method Sentiment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sentiment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sentiment[]    findAll()
 * @method Sentiment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SentimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sentiment::class);
    }

//    /**
//     * @return Sentiment[] Returns an array of Sentiment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sentiment
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
