<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    // /**
    //  * @return Car[] Returns an array of Car objects
    //  */
    
    public function searchCar($criteria)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.brand', 'brand')
            ->where('brand.name = :brandName')
            ->setParameter('brandName', $criteria['brand']->getName())
            ->andWhere('c.model LIKE :model')
            ->setParameter('model', "%{$criteria['model']}%")
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    public function findLastThreeCarsByDate() 
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
