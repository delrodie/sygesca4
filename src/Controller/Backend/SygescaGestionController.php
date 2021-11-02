<?php

namespace App\Controller\Backend;

use App\Entity\Compte;
use App\Entity\Sygesca3\District;
use App\Entity\Sygesca3\Region;
use App\Repository\RegionReposiroty;
use App\Utilities\GestionCotisation;
use App\Utilities\GestionScout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/sygesca/gestion")
 */
class SygescaGestionController extends AbstractController
{
	private $_scout;
	private $_cotisation;
	private $regionReposiroty;
	
	public function __construct(GestionScout $_scout, GestionCotisation $_cotisation, RegionReposiroty $regionReposiroty)
	{
		$this->_scout = $_scout;
		$this->_cotisation = $_cotisation;
		$this->regionReposiroty = $regionReposiroty;
	}
    /**
     * @Route("/", name="sygesca_gestion_liste", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
	    $user = $this->getUser();
	    $role = $user->getRoles();
	    // Si l'utilisateur a pour role 0 User alors verifier si c'est un regional
	    if ($role[0] === 'ROLE_USER'){
		    if ($role[1] === 'ROLE_REGION'){
			    $compte = $this->getDoctrine()->getRepository(Compte::class)->findOneBy(['user'=>$user->getId()]);
			
			    //return $this->redirectToRoute('sygesca_gestion_region',['regionSlug'=>$compte->getRegion()->getSlug()]);
			
			    $districtID = $request->get('district');
			    $result = $this->region($compte, $districtID);
				
				return $this->render($result['template'],[
					'districts' => $result['districts'],
					'region' => $result['region'],
					'district' => $result['district']
				]);
		    }
	    }
		
		// Declaration variable
	    $region = null;
		
		// Request
		$regionID = $request->get('region');
		
		if ($regionID){
			$region = $this->regionReposiroty->findOneBy(['id'=>$regionID]);
			$template = 'sygesca_gestion/region.html.twig';
		}else{
			$template = 'sygesca_gestion/index.html.twig';
		}
		
        return $this->render($template, [
            'regions' => $this->regionReposiroty->findAll(),
	        'region' => $region
        ]);
    }
	
	/**
	 * @Route("/ajax", name="sygesca_gestion_ajax", methods={"GET","POSt"})
	 */
	public function ajax(Request $request)
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$region = $request->get('region');
		$district = $request->get('district');
		
		$annee = $this->_cotisation->annee();
		$scouts = $this->_scout->getListScout($annee,$region,$district);
		
		return $this->json($scouts);
		
	}
	
	/**
	 * @Route("/encours/", name="sygesca_gestion_nonvalid", methods={"GET","POST"})
	 */
	public function nonvalid(Request $request)
	{
		$this->_scout->getListNonValid();
		return $this->render('sygesca_gestion/nonvalid.html.twig',[
			'regions' => $this->regionReposiroty->findAll()
		]);
		
	}
	/**
	 * @Route("/encours/ajax", name="sygesca_gestion_nonvalid_ajax", methods={"GET","POSt"})
	 */
	public function nonvalidAjax(Request $request)
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$region = $request->get('region');
		$district = $request->get('district');
		
		$scouts = $this->_scout->getListNonValid($region);
		
		return $this->json($scouts);
		
	}
	
	/**
	 * @param $compte
	 * @param $districtID
	 * @return array
	 */
	protected function region($compte, $districtID)
	{
		$region = $this->getDoctrine()->getRepository(Region::class)->findOneBy(['slug'=>$compte->getRegion()->getSlug()]);
		$district = null;
		if ($districtID){
			$district = $this->getDoctrine()->getRepository(District::class)->findOneBy(['id'=>$districtID]);
			$template = 'sygesca_gestion/region_districts.html.twig';
		}else{
			$template = 'sygesca_gestion/region_liste.html.twig';
		}
		
		return  [
			'template' => $template,
			'districts' => $this->getDoctrine()->getRepository(District::class)->findBy(['region'=>$region->getId()],['nom'=>"ASC"]),
			'region' => $this->regionReposiroty->findOneBy(['id'=>$region->getId()]),
			'district' => $district
		];
	}
	
}
