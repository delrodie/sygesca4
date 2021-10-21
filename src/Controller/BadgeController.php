<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Entity\Membre;
use App\Utilities\GestionCotisation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/badge")
 */
class BadgeController extends AbstractController
{
	private $_cotisation;
	
	public function __construct(GestionCotisation $_cotisation)
	{
		$this->_cotisation = $_cotisation;
	}
	
    /**
     * @Route("/", name="print_badge")
     */
    public function index(Request $request): Response
    {
	    //Initialisation
	    $encoders = [new XmlEncoder(), new JsonEncoder()];
	    $normalizers = [new ObjectNormalizer()];
	    $serializer = new Serializer($normalizers, $encoders);
		
		$session = $request->getSession()->get('matricule');
		
		$scout = $this->getDoctrine()->getRepository(Membre::class)->findOneBy(['matricule'=>$session]);

        return $this->render('badge/index.html.twig', [
            'scout' => $scout,
        ]);
    }
}
