<?php

namespace App\Repository;

use App\Entity\Sygesca3\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictReposiroty extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, District::class);
    }
	
	public function findListByRegionActive()
	{ //dd($equipes[0]);
		return $this
			->createQueryBuilder('d')
			->addSelect('r')
			->leftJoin('d.region', 'r')
			->where('r.id BETWEEN 4 AND 18')
			->orderBy('r.nom', 'ASC')
			->addOrderBy('d.nom', 'ASC')
			->getQuery()->getResult()
			;
	}
	
	public function listeEquipe($region = null){
		$query =  $this
			->createQueryBuilder('d')
			->where('d.nom LIKE :equipe');
		if ($region){
			$query->andWhere('d.region = :region')
				->setParameter('region', $region);
		}
		$result = $query->setParameter('equipe', '%equipe%')
						->getQuery()->getResult();
				
		return $result;
	}

    // /**
    //  * @return District[] Returns an array of District objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?District
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
