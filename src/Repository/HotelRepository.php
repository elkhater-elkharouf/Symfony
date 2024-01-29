<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Hotel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @method Hotel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hotel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hotel[]    findAll()
 * @method Hotel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HotelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry , PaginatorInterface $paginator)  {
        parent::__construct($registry, Hotel::class);
        $this->paginator =$paginator;
    }

    // /**
    //  * @return Hotel[] Returns an array of Hotel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hotel
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Recuperer les hotels en lien avec une recherche
     * @return PaginationInterface
     */

    public function findSearch(SearchData $search):PaginationInterface

    {
        $query=$this
            ->createQueryBuilder('h');


        if (!empty($search->q)){
            $query=$query
                ->andWhere('h.name LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }
        if (!empty($search->min)){
            $query=$query
                ->andWhere('h.prix >= :min')
                ->setParameter('min',$search->min);

        }
        if (!empty($search->max)){
            $query=$query
                ->andWhere('h.prix <= :max')
                ->setParameter('max',$search->max);

        }
        if (!empty($search->promo)){
            $query=$query
                ->andWhere('h.promotion =1');

        }

        if (!empty($search->a)){
            $query=$query
                ->andWhere('h.adresse LIKE :a')
                ->setParameter('a',"%{$search->a}%");
        }
      $query= $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            2
        );

    }
}