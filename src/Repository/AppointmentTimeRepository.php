<?php

namespace App\Repository;

use App\Entity\AppointmentTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppointmentTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentTime[]    findAll()
 * @method AppointmentTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentTime::class);
    }

    // /**
    //  * @return AppointmentTime[] Returns an array of AppointmentTime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AppointmentTime
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
