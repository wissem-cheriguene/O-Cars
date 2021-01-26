<?php

namespace App\Repository;

use App\Entity\Rental;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
            ->setParameter('car', $car)
            ->orderBy('r.startingDate', 'ASC')
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
