<?php

namespace App\Repository;

use App\Entity\Userr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\DBAL\Connection;

/**
 * @extends ServiceEntityRepository<Userr>
* @implements PasswordUpgraderInterface<Userr>
 *
 * @method Userr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userr[]    findAll()
 * @method Userr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserrRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userr::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Userr) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Userr[] Returns an array of Userr objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Userr
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByFilters(array $filters): array
{
    $queryBuilder = $this->createQueryBuilder('u');

    if (isset($filters['name'])) {
        $queryBuilder->andWhere('u.username LIKE :name')
            ->setParameter('name', '%' . $filters['name'] . '%');
    }

    if (isset($filters['email'])) {
        $queryBuilder->andWhere('u.mail LIKE :email')
            ->setParameter('email', '%' . $filters['email'] . '%');
    }

    return $queryBuilder->getQuery()->getResult();
}

public function countByGenre(string $genre): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.idUser)')
        ->andWhere('u.sexe = :genre')
        ->setParameter('genre', $genre)
        ->getQuery()
        ->getSingleScalarResult();
}

public function countByAgeRange(array $ageRange): int
{
    
        $qb = $this->createQueryBuilder('u');

    return $qb
        ->select('COUNT(u.idUser)')
        ->andWhere($qb->expr()->between('u.age', ':minAge', ':maxAge'))
        ->setParameter('minAge', $ageRange[0])
        ->setParameter('maxAge', $ageRange[1])
        ->getQuery()
        ->getSingleScalarResult();
}
public function findAllSortedBy(string $criteria): array
{
    return $this->createQueryBuilder('u')
        ->orderBy('u.' . $criteria, 'ASC')
        ->getQuery()
        ->getResult();
}

// UserrRepository.php

public function findUsersByString($searchString)
{
    return $this->createQueryBuilder('u')
        ->where('u.mail LIKE :search')
        ->setParameter('search', '%' . $searchString . '%')
        ->getQuery()
        ->getResult();
}


}