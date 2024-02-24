<?php

namespace App\Repository;

use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Schedule>
 *
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    public function findOneByDateTime($routeId, $startDate, $endDate, $startHour, $startMinute, $endHour, $endMinute)
    {
        return $this->createQueryBuilder('s')
            ->where('s.route_id = :routeId')
            ->andWhere('s.start_date = :startDate')
            ->andWhere('s.end_date = :endDate')
            ->andWhere('s.start_hour = :startHour')
            ->andWhere('s.start_minute = :startMinute')
            ->andWhere('s.end_hour = :endHour')
            ->andWhere('s.end_minute = :endMinute')
            ->setParameter('routeId', $routeId)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('startHour', $startHour)
            ->setParameter('startMinute', $startMinute)
            ->setParameter('endHour', $endHour)
            ->setParameter('endMinute', $endMinute)
            ->getQuery()
            ->getOneOrNullResult();
    }    

    /**
     * Find all entities in descending order.
     */
    public function findAllDesc()
    {
        return $this->findBy([], ['id' => 'DESC']);
    }


    // /**
    //  * Find all entities in descending order.
    //  */
    // public function findSection()
    // {
    //     return $this->findBy([], ['id' => 'DESC']);
    // }

}
