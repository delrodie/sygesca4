<?php
	
	namespace App\Utilities;
	
	use App\Entity\Sygesca3\District;
	use App\Entity\Sygesca3\Groupe;
	use App\Entity\Sygesca3\Region;
	use Doctrine\ORM\EntityManagerInterface;
	
	class Utility
	{
		private $_em;
		
		public function __construct(EntityManagerInterface $_em)
		{
			$this->_em = $_em;
		}
		
		/**
		 * Liste des regions avec les statistiques
		 *
		 * @return array
		 */
		public function regionList(): array
		{
			$regions = $this->_em->getRepository(Region::class)->findAll();
			$list=[];$i=0;
			foreach ($regions as $region){
				// Variable
				$nb_district = count($this->_em->getRepository(District::class)->findBy(['region'=>$region->getId()]));
				$nb_equipe_district = count($this->_em->getRepository(District::class)->listeEquipe($region->getId()));
				$nb_groupe = count($this->_em->getRepository(Groupe::class)->findByRegionOrDistrict($region->getId()));
				$nb_equipe_groupe = count($this->_em->getRepository(Groupe::class)->listEqupe($region->getId()));
				$list[$i++]=[
					'region_nom' => $region->getNom(),
					'region_code' => $region->getCode(),
					'region_slug' => $region->getSlug(),
					'nombre_district' => $nb_district - $nb_equipe_district,
					'nombre_groupe' => $nb_groupe - $nb_equipe_groupe,
				];
			}
			
			return $list;
		}
		
		public function getNombreEntite()
		{
			$total_district = count($this->_em->getRepository(District::class)->findListByRegionActive());
			$total_equipe_district = count($this->_em->getRepository(District::class)->listeEquipe());
			$total_groupe = count($this->_em->getRepository(Groupe::class)->findListByRegionActive());
			$total_equipe_groupe = count($this->_em->getRepository(Groupe::class)->listEqupe());
			
			return $entite = [
				'region' => count($this->_em->getRepository(Region::class)->findListActive()),
				'district' => $total_district - $total_equipe_district,
				'groupe' => $total_groupe - $total_equipe_groupe
			];
		}
		
	}
