<?php

namespace App\Utilities;

use App\Entity\Membre;
use App\Entity\Sygesca3\Fonctions;
use App\Entity\Sygesca3\Region;
use App\Entity\Sygesca3\Scout;
use Doctrine\ORM\EntityManagerInterface;

class GestionScout
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFonctionByAge($date)
    {
        $age = date('Y') - date('Y', strtotime($date));

        return $this->entityManager->getRepository(Fonctions::class)->findByAge($age);

    }
	
	public function getByNomAndPrenoms($nom, $prenom)
	{
		$sql = '
			SELECT s
			FROM App:Sygesca3\Region as s
		';
		$query = $this->entityManager->createQuery($sql);
		
		return $query->getResult();
	}
	
	/**
	 * Generation du matricule
	 *
	 * @param $region
	 * @return string
	 */
	public function matricule($region): string
	{
		$region_code = $this->entityManager->getRepository(Region::class)->findOneBy(['id'=>$region])->getCode();
		
		// Verification de non existence
		$stop = true;
		while ($stop){
			$matricule = $region_code.''.$this->code_aleatoire().''.$this->lettre_aleatoire();
			$exist = $this->entityManager->getRepository(Membre::class)->findOneBy(['matricule'=>$matricule]);
			if ($exist) $stop = true;
			else $stop = false;
		}
		
		return $matricule;
    }
	
	/**
	 * @param object $scout
	 * @param string $region_code
	 * @param int $id
	 * @return bool
	 */
	public function carte(object $scout, string $region_code, int $id): bool
	{
		$mois_encours = Date('m', time());
		if ($mois_encours > 9){
			$an = Date('y', time())+1;
		}else{
			$an = Date('y', time());
		}
		
		if ($id < 10){
			$num = '0000'.$id;
		}elseif($id < 100){
			$num = '000'.$id;
		}elseif ($id < 1000){
			$num = '00'.$id;
		}elseif ($id < 10000){
			$num = '0'.$id;
		}else{
			$num = $id;
		}
		
		$carte = $region_code.''.$an.'-'.$num;
		$scout->setCarte($carte); //dd($scout);
		$this->entityManager->flush();
		
		return true;
	}
	
	/**
	 * Generation du code aleatoire pour constituer le matricule du scout
	 *
	 * @return int
	 */
	private function code_aleatoire():int
	{
		return mt_rand(10000,99999);
	}
	
	/**
	 * Generation du lettre aleatoire pour constituer le matricule du scout
	 * @return string
	 */
	private function lettre_aleatoire():string
	{
		// Liste des lettres de l'alphabet
		$alphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		
		return $alphabet[rand(0,25)];
	}

}
