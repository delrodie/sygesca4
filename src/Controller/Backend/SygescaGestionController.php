<?php

namespace App\Controller\Backend;

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
    public function index(): Response
    {
        return $this->render('sygesca_gestion/index.html.twig', [
            'regions' => $this->regionReposiroty->findAll(),
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
		
		$annee = $this->_cotisation->annee();
		$scouts = $this->_scout->getListScout($annee);
		
		return $this->json($scouts);
		
	}
}
