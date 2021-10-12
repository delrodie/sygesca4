<?php

namespace App\Repository;

use App\Entity\Sygesca3\Scout;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Scout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scout[]    findAll()
 * @method Scout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoutRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scout::class);
    }


    public function findOneByMatriculeOrSlug($slug)
    {
        return $this
            ->createQueryBuilder('s')
            ->where('s.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()->getResult()
        ;
    }
}