<?php
	
	namespace App\Utilities;
	
	use App\Entity\Cotisation;
	use App\Entity\Sygesca3\Region;
	use App\Repository\CotisationRepository;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionCotisation
	{
		private $_em;
		private $cotisationRepository;
		
		public function __construct(EntityManagerInterface $_em, CotisationRepository $cotisationRepository)
		{
			$this->_em = $_em;
			$this->cotisationRepository = $cotisationRepository;
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
		
		public function statistiquesRegion($annee, $region=null, $district=null)
		{
			$regions = $this->_em->getRepository(Region::class)->findListActive();
			
			$lists=[];$i=0;
			foreach($regions as $region){
				$lists[$i++] = [
					'nom' => $region->getNom(),
					'total' => count($this->cotisationRepository->findList($annee,$region->getId())),
					'jeune' => count($this->cotisationRepository->findByStatut($annee,'Jeune',$region->getId())),
					'adulte' => count($this->cotisationRepository->findByStatut($annee,'Adulte',$region->getId())),
					'homme' => count($this->cotisationRepository->findBySexe($annee,'HOMME',$region->getId())),
					'femme' => count($this->cotisationRepository->findBySexe($annee,'FEMME',$region->getId())),
					'louveteau' => count($this->cotisationRepository->findByBranche($annee,'Jeune','LOUVETEAU',$region->getId())),
					'eclaireur' => count($this->cotisationRepository->findByBranche($annee,'Jeune','ECLAIREUR',$region->getId())),
					'cheminot' => count($this->cotisationRepository->findByBranche($annee,'Jeune','CHEMINOT',$region->getId())),
					'routier' => count($this->cotisationRepository->findByBranche($annee,'Jeune','ROUTIER',$region->getId())),
				];
			}
			
			return $lists;
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
		
		public function branche(): array
		{
			$branche = [
				'meute' => 'LOUVETEAU',
				'troupe' => 'ECLAIREUR',
				'cheminot' => 'CHEMINOT',
				'routier' => 'ROUTIER'
			];
			
			return $branche;
		}
	}
