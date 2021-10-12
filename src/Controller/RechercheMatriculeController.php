<?php

namespace App\Controller;

use App\Entity\Sygesca3\Scout;
use App\Utilities\GestionScout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recherche/matricule")
 */
class RechercheMatriculeController extends AbstractController
{
    /**
     * @var GestionScout
     */
    private $gestionScout;

    public function __construct(GestionScout $gestionScout)
    {

        $this->gestionScout = $gestionScout;
    }
    /**
     * @Route("/{slug}", name="recherche_matricule_response")
     */
    public function index(Request $request, $slug): Response
    {
        $scout = $this->getDoctrine()->getRepository(Scout::class, 'sygesca')->findOneBy(['slug'=>$slug]);

        return $this->render('recherche_matricule/index.html.twig', [
            'scout' => $scout,
        ]);
    }
}
