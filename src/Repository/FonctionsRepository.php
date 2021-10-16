<?php

namespace App\Repository;

use App\Entity\Sygesca3\Fonctions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fonctions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fonctions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fonctions[]    findAll()
 * @method Fonctions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FonctionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fonctions::class);
    }

    public function findByAge($age)
    { //dd($age);
        $query = $this->createQueryBuilder('s');
        if ($age < 12)
            $query->where('s.id = 1');
        elseif ($age == 12 )
            $query->where('s.id <= 2');
        elseif($age >=13 and $age < 15)
            $query->where('s.id = 2');
        elseif ($age == 15 )
            $query->where('s.id >= 2 AND s.id <= 3');
        elseif($age >=15 and $age < 18)
            $query->where('s.id = 3');
        elseif($age >=18 and $age < 21)
            $query->where('s.id >= 4');
        else
            $query->where('s.id >= 5');

        return $query->getQuery()->getResult();
    }



    // /**
    //  * @return Fonctions[] Returns an array of Fonctions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fonctions
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
