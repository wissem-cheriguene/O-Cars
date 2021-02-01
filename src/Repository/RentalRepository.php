<?php

namespace App\Repository;

use stdClass;
use App\Entity\Rental;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Rental|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rental|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rental[]    findAll()
 * @method Rental[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RentalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rental::class);
    }

    // /**
    //  * @return Rental[] Returns an array of Rental objects
    //  */
    
    public function findRentalsByCar($car)
    {
        return $this->createQueryBuilder('r')
            ->select('r.startingDate','r.endingDate')
            ->andWhere('r.car = :car')
            // Retire les dates location annulé par le propriétaire du tableau
            // des dates d'indisponibilité
            ->andWhere('r.status < 3')
            ->setParameter('car', $car)
            ->orderBy('r.startingDate', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findRentalsByUser($user)
    {

        return $this->createQueryBuilder('r')
            ->andWhere('r.user= :user')
            ->setParameter('user', $user)
            ->orderBy('r.status', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // https://stackoverflow.com/questions/14218444/using-a-arraycollection-as-a-parameter-in-a-doctrine-query
    public function findOwnerByBookings($car)
    {

        return $this->createQueryBuilder('r')
            ->join('r.car','car')
            ->andWhere('r in (:car)')
            ->setParameter('car', $car)
            ->orderBy('r.status', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Rental
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
