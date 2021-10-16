<?php

namespace App\Repository;

use App\Entity\Adherant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Adherant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adherant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adherant[]    findAll()
 * @method Adherant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdherantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adherant::class);
    }

    // /**
    //  * @return Adherant[] Returns an array of Adherant objects
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
    public function findOneBySomeField($value): ?Adherant
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
