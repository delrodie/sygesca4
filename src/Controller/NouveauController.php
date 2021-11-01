<?php

namespace App\Controller;

use App\Entity\Sygesca3\Fonctions;
use App\Entity\Sygesca3\Region;
use App\Utilities\GestionAdhesion;
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
 * @Route("/nouveau")
 */
class NouveauController extends AbstractController
{
	private $_scout;
	private $_adhesion;
	
	public function __construct(GestionScout $_scout, GestionAdhesion $_adhesion)
	{
		
		$this->_scout = $_scout;
		$this->_adhesion = $_adhesion;
	}
	
    /**
     * @Route("/", name="adhesion_nouveau_formulaire")
     */
    public function index(): Response
    {
        return $this->render('nouveau/index.html.twig', [
	        'regions' => $this->getDoctrine()->getRepository(Region::class)->findAll(),
	        'fonctions' => $this->getDoctrine()->getRepository(Fonctions::class)->findAll()
        ]);
    }
	
	/**
	 * @Route("/new", name="adhesion_nouveau_new", methods={"GET","POST"})
	 */
	public function new(Request $request)
	{
		//Initialisation 1636392395.43237087
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders); //dd($request);
		
		$message = $this->_adhesion->formulaire($request->request);
		
		return $this->json($message);
	}
	
}
