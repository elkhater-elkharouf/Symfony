<?php

namespace App\Repository;

use App\Entity\Voiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Data\SearchData;

/**
 * @method Voiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voiture[]    findAll()
 * @method Voiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,PaginatorInterface $paginator)
    {
        parent::__construct($registry, Voiture::class);
        $this->paginator =$paginator;
    }

    // /**
    //  * @return Voiture[] Returns an array of Voiture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Voiture
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
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
            ->createQueryBuilder('v');


        if (!empty($search->marque)){
            $query=$query
                ->andWhere('v.marque LIKE :marque')
                ->setParameter('marque',"%{$search->marque}%");
        }
        if (!empty($search->min)){
            $query=$query
                ->andWhere('v.prix >= :min')
                ->setParameter('min',$search->min);

        }
        if (!empty($search->max)){
            $query=$query
                ->andWhere('v.prix <= :max')
                ->setParameter('max',$search->max);

        }


        if (!empty($search->modele)){
            $query=$query
                ->andWhere('v.modele LIKE :modele')
                ->setParameter('modele',"%{$search->modele}%");
        }
        if (!empty($search->couleur)){
            $query=$query
                ->andWhere('v.couleur LIKE :couleur')
                ->setParameter('couleur',"%{$search->couleur}%");
        }

        $query= $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            3
        );

    }
}