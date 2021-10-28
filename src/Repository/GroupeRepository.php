<?php

namespace App\Repository;

use App\Entity\Sygesca3\Groupe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }
	
	public function findByRegionOrDistrict($region = null, $district = null)
	{
		$query = $this->createQueryBuilder('g')
			->addSelect('d')
			->addSelect('r')
			->leftJoin('g.district', 'd')
			->leftJoin('d.region', 'r')
			->orderBy('g.paroisse', 'ASC')
			;
		if ($region){
			$query
				->where('r.id = :region')
				->setParameter('region', $region)
			;
		}elseif ($district){
			$query
				->where('d.id = :district')
				->setParameter('district', $district)
			;
		}
		$qb = $query->getQuery()->getResult();
		
		return $qb;
	}

    // /**
    //  * @return Groupe[] Returns an array of Groupe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Groupe
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
