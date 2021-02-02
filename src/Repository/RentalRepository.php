<?php

namespace App\Repository;

use PDO;
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
        
        // $r = $this->createQueryBuilder('r')
        // ->join('r.car','car')
        // ->andWhere('r in (:car)')
        // ->setParameter('car', $car)
        // // ->orderBy('r.status', 'ASC')
        // ->getQuery()
        // // ->getResult()
        // ;
        // dd($r);

        $em = $this->getEntityManager();

        //SELECT * FROM `rental` INNER JOIN car ON rental.car_id = car.id WHERE `car`.`user_id` = 5
        $RAW_QUERY = 'SELECT * FROM `rental` INNER JOIN car ON rental.car_id = car.id WHERE `car`.`user_id` = :userId';
        
        $statement = $em->getConnection()->prepare($RAW_QUERY);
        // Set parameters 
        $statement->bindValue('userId', 5);
        $statement->execute();

        $r = $statement->fetchAll(PDO::FETCH_CLASS);
        dd($r);

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
