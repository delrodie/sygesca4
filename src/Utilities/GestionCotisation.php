<?php
	
	namespace App\Utilities;
	
	use App\Entity\Cotisation;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionCotisation
	{
		private $_em;
		
		public function __construct(EntityManagerInterface $_em)
		{
			$this->_em = $_em;
		}
		
		/**
		 * @param object $scout
		 * @param object $fonction
		 * @param int|null $montant
		 * @param string|null $phone
		 * @return bool
		 */
		public function save(object $scout, object $fonction, int $montant=null, string $phone=null): bool
		{
			$cotisation = new Cotisation();
			$annee = $this->annee();
			$cotisation->setAnnee($annee);
			$cotisation->setFonction($fonction->getLibelle());
			$cotisation->setCarte($scout->getCarte());
			$cotisation->setMontant($montant);
			$cotisation->setMontantSansFrais($fonction->getMontant());
			$cotisation->setRistourne($fonction->getRistourne());
			$cotisation->setMembre($scout);
			$cotisation->setTelephone($phone);
			
			$scout->setCotisation($annee);
			
			$this->_em->persist($cotisation);
			$this->_em->flush();
			
			return true;
		}
		
		/**
		 * @return string
		 */
		public function annee(): string
		{
			$mois_encours = Date('m', time());
			if ($mois_encours > 9){
				$debut_annee = Date('Y', time());
				$fin_annee = Date('Y', time())+1;
				//$an = Date('y', time())+1;
			}else{
				$debut_annee = Date('Y', time())-1;
				$fin_annee = Date('Y', time());
				//$an = Date('y', time());
			}
			
			$annee = $debut_annee.'-'.$fin_annee;
			
			return $annee;
		}
	}
