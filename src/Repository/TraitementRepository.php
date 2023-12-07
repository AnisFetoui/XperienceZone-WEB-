<?php

namespace App\Repository;

use App\Entity\Traitements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Traitements>
 *
 * @method Traitements|null find($id, $lockMode = null, $lockVersion = null)
 * @method Traitements|null findOneBy(array $criteria, array $orderBy = null)
 * @method Traitements[]    findAll()
 * @method Traitements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TraitementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traitements::class);
    }

//    /**
//     * @return Traitements[] Returns an array of Traitements objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Traitements
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findReclamationsByString($searchString)
{
    return $this->createQueryBuilder('r')
        ->where('r.resume LIKE :search')
        ->setParameter('search', '%' . $searchString . '%')
        ->getQuery()
        ->getResult();
}


public function countByStat(string $stat): int
{
    return $this->createQueryBuilder('t')
        ->select('COUNT(t)')
        ->where('t.stat = :stat')
        ->setParameter('stat', $stat)
        ->getQuery()
        ->getSingleScalarResult();
}

public function countByTypeAndStat(string $stat): array
{
    return $this->createQueryBuilder('t')
        ->select('t.typer, COUNT(t.idt) as count')
        ->where('t.stat = :stat')
        ->groupBy('t.typer')
        ->setParameter('stat', $stat)
        ->getQuery()
        ->getResult();
}
}
