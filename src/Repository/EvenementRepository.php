<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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



/*public function countEventsByLocation($wileya)
    {
        return $this->createQueryBuilder('e')
            ->select('e.lieuEvent, COUNT(e.idEvent) as eventCount')
            ->groupBy('e.lieuEvent')
            ->getQuery()
            ->getResult();
    }*/
    public function countEventsByLocation()
    {
        return $this->createQueryBuilder('e')
            ->select('e.lieuEvent AS lieuEvent, COUNT(e.idEvent) AS eventCount')
            ->groupBy('e.lieuEvent')
            ->getQuery()
            ->getResult();
    }


  /*  public function findEvenementByString($searchString)
    {
        return $this->createQueryBuilder('r')
            ->where('r.nomEvent LIKE :search')
            ->setParameter('search', '%' . $searchString . '%')
            ->getQuery()
            ->getResult();
    }*/

   // EvenementRepository.php

   public function search($searchTerm)
   {
       return $this->createQueryBuilder('e')
           ->where('e.nomEvent LIKE :searchTerm')
           ->setParameter('searchTerm', '%' . $searchTerm . '%')
           ->getQuery()
           ->getResult();
   }
   
   public function searchByTerm($searchTerm)
   {
       $queryBuilder = $this->createQueryBuilder('e');
   
       if ($searchTerm) {
           $queryBuilder->andWhere('e.nomEvent LIKE :searchTerm OR e.lieuEvent LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
       }
   
       return $queryBuilder->getQuery();
   }

}