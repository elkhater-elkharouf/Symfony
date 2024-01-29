<?php

namespace App\Repository;

use App\Entity\Reservationv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservationv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservationv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservationv[]    findAll()
 * @method Reservationv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservationv::class);
    }

    // /**
    //  * @return Reservationv[] Returns an array of Reservationv objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reservationv
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
