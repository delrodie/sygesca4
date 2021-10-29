<?php

namespace App\Controller\Backend;

use App\Entity\Sygesca3\Region;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sygesca/region")
 */
class SygescaRegionController extends AbstractController
{
	
	
	private $utility;
	
	public function __construct(Utility $utility)
	{
		$this->utility = $utility;
	}
	
    /**
     * @Route("/", name="sygesca_region_index")
     */
    public function index(): Response
    {
		//dd();
        return $this->render('sygesca_region/index.html.twig', [
            'regions' => $this->utility->regionList(),
	        'entite' => $this->utility->getNombreEntite()
        ]);
    }
}
