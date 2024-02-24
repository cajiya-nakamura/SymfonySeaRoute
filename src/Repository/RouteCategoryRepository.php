<?php

namespace App\Repository;

use App\Entity\RouteCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RouteCategory>
 *
 * @method RouteCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouteCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouteCategory[]    findAll()
 * @method RouteCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouteCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RouteCategory::class);
    }

    //    /**
    //     * @return RouteCategory[] Returns an array of RouteCategory objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?RouteCategory
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
