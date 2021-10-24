<?php

namespace App\Repository;

use App\Entity\Cotisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cotisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cotisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cotisation[]    findAll()
 * @method Cotisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotisation::class);
    }
	
	public function findList($annee, $region=null, $district=null, $groupe=null)
	{
		$qb = $this->createQueryBuilder('c')
			->addSelect('m')
			->addSelect('g')
			->addSelect('d')
			->addSelect('r')
			->addSelect('s')
			->leftJoin('c.membre', 'm')
			->leftJoin('m.groupe', 'g')
			->leftJoin('g.district', 'd')
			->leftJoin('d.region', 'r')
			->leftJoin('m.statut', 's')
			->orderBy('m.nom', 'ASC')
			->addOrderBy('m.prenoms', 'ASC')
			->where('c.annee = :annee')
		;
		if ($region){
			$qb->andWhere('r.id = :region')
				->setParameters([
					'region' => $region,
					'annee' => $annee
				])
			;
		}elseif ($district){
			$qb->andWhere('d.id = :district')
				->setParameters([
					'district' => $district,
					'annee' => $annee
				])
			;
		}elseif ($groupe){
			$qb->andWhere('g.id = :groupe')
				->setParameters([
					'groupe' => $groupe,
					'annee' => $annee
				]);
		}else{
			$qb->setParameter('annee', $annee);
		}
		$query = $qb->getQuery()->getResult(); //dd($query);
		
		return $query;
	}
	
	public function findByStatut($annee, $statut, $region=null, $district=null, $groupe=null)
	{
		$qb = $this->createQueryBuilder('c')
			->addSelect('m')
			->addSelect('g')
			->addSelect('d')
			->addSelect('r')
			->addSelect('s')
			->leftJoin('c.membre', 'm')
			->leftJoin('m.groupe', 'g')
			->leftJoin('g.district', 'd')
			->leftJoin('d.region', 'r')
			->leftJoin('m.statut', 's')
			->orderBy('m.nom', 'ASC')
			->addOrderBy('m.prenoms', 'ASC')
			->where('c.annee = :annee')
			->andWhere('s.libelle = :statut')
		;
		if ($region){
			$qb->andWhere('r.id = :region')
				->setParameters([
					'region' => $region,
					'annee' => $annee,
					'statut' => $statut,
				])
			;
		}elseif ($district){
			$qb->andWhere('d.id = :district')
				->setParameters([
					'district' => $district,
					'annee' => $annee,
					'statut' => $statut
				])
			;
		}elseif ($groupe){
			$qb->andWhere('g.id = :groupe')
				->setParameters([
					'groupe' => $groupe,
					'annee' => $annee,
					'statut' => $statut
				]);
		}else{
			$qb->setParameter('annee', $annee);
			$qb->setParameter('statut', $statut);
		}
		$query = $qb->getQuery()->getResult(); //dd($query);
		
		return $query;
	}
	
	public function findByBranche($annee, $statut, $branche, $region=null, $district=null, $groupe=null)
	{
		$qb = $this->createQueryBuilder('c')
			->addSelect('m')
			->addSelect('g')
			->addSelect('d')
			->addSelect('r')
			->addSelect('s')
			->leftJoin('c.membre', 'm')
			->leftJoin('m.groupe', 'g')
			->leftJoin('g.district', 'd')
			->leftJoin('d.region', 'r')
			->leftJoin('m.statut', 's')
			->orderBy('m.nom', 'ASC')
			->addOrderBy('m.prenoms', 'ASC')
			->where('c.annee = :annee')
			->andWhere('s.libelle = :statut')
			->andWhere('m.branche LIKE :branche')
		;
		if ($region){
			$qb->andWhere('r.id = :region')
				->setParameters([
					'region' => $region,
					'annee' => $annee,
					'statut' => $statut,
					'branche' => '%'.$branche.'%',
				])
			;
		}elseif ($district){
			$qb->andWhere('d.id = :district')
				->setParameters([
					'district' => $district,
					'annee' => $annee,
					'statut' => $statut,
					'branche' => '%'.$branche.'%',
				])
			;
		}elseif ($groupe){
			$qb->andWhere('g.id = :groupe')
				->setParameters([
					'groupe' => $groupe,
					'annee' => $annee,
					'statut' => $statut,
					'branche' => '%'.$branche.'%',
				]);
		}else{
			$qb->setParameter('annee', $annee);
			$qb->setParameter('statut', $statut);
			$qb->setParameter('branche', '%'.$branche.'%');
		}
		$query = $qb->getQuery()->getResult(); //dd($query);
		
		return $query;
	}
	
	public function findBySexe($annee, $sexe, $region=null, $district=null, $groupe=null)
	{
		$qb = $this->createQueryBuilder('c')
			->addSelect('m')
			->addSelect('g')
			->addSelect('d')
			->addSelect('r')
			->addSelect('s')
			->leftJoin('c.membre', 'm')
			->leftJoin('m.groupe', 'g')
			->leftJoin('g.district', 'd')
			->leftJoin('d.region', 'r')
			->leftJoin('m.statut', 's')
			->orderBy('m.nom', 'ASC')
			->addOrderBy('m.prenoms', 'ASC')
			->where('c.annee = :annee')
			->andWhere('m.sexe = :sexe')
		;
		if ($region){
			$qb->andWhere('r.id = :region')
				->setParameters([
					'region' => $region,
					'annee' => $annee,
					'sexe' => $sexe,
				])
			;
		}elseif ($district){
			$qb->andWhere('d.id = :district')
				->setParameters([
					'district' => $district,
					'annee' => $annee,
					'sexe' => $sexe
				])
			;
		}elseif ($groupe){
			$qb->andWhere('g.id = :groupe')
				->setParameters([
					'groupe' => $groupe,
					'annee' => $annee,
					'sexe' => $sexe
				]);
		}else{
			$qb->setParameter('annee', $annee);
			$qb->setParameter('sexe', $sexe);
		}
		$query = $qb->getQuery()->getResult(); //dd($query);
		
		return $query;
	}
	

    // /**
    //  * @return Cotisation[] Returns an array of Cotisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cotisation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
