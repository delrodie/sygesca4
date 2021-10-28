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
		
		public function regionList()
		{
			$regions = $this->_em->getRepository(Region::class)->findAll();
			$list=[];$i=0;
			foreach ($regions as $region){
				$list[$i++]=[
					'region_nom' => $region->getNom(),
					'region_code' => $region->getCode(),
					'region_slug' => $region->getSlug(),
					'nombre_district' => count($this->_em->getRepository(District::class)->findBy(['region'=>$region->getId()])),
					'nombre_groupe' => count($this->_em->getRepository(Groupe::class)->findByRegionOrDistrict($region->getId()))
				];
			}
			
			return $list;
		}
		
	}
