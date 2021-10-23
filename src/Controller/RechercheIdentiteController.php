<?php

namespace App\Controller;

use App\Entity\Sygesca3\Region;
use App\Entity\Sygesca3\Scout;
use App\Utilities\GestionScout;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/recherche/identite")
 */
class RechercheIdentiteController extends AbstractController
{
	private $gestionScout;
	
	public function __construct(GestionScout $gestionScout)
	{
		$this->gestionScout = $gestionScout;
	}
    /**
     * @Route("/", name="recherche_identite")
     */
    public function index(): Response
    {
        return $this->render('recherche_identite/index.html.twig', [
            'controller_name' => 'RechercheIdentiteController',
        ]);
    }
	
	/**
	 * @Route("/liste", name="recherche_identite_liste", methods={"GET","POST"})
	 */
	public function liste(Request $request)
	{
		$slug = $request->getSession()->get('identiteRecherche');
		
		return $this->redirectToRoute('recherche_matricule_response',['slug'=>$slug]);
		
		/*return $this->forward('App\Controller\RechercheMatriculeController::index',[
			'slug' => $slug
		]);*/
	}

    /**
     * @Route("/{nom}/{prenom}/{date}", name="recherche_identite_resultat", methods={"GET","POST"})
     */
    public function resultat(Request $request, $nom, $prenom, $date)
    {
        //Initialisation
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
		
	
	    $scout = $this->getDoctrine()->getRepository(Scout::class, 'sygesca')
		    ->findOneBy(['nom'=>$nom, 'prenoms'=> $prenom, 'datenaiss' => $date]); //dd($scout);
		
		if (!$scout){
			$identite = [
				'nom' => $nom,
				'prenom' => $prenom,
				'slug' => false
			];
			return $this->json($identite);
		}else{
			$request->getSession()->set('identiteRecherche', $scout->getSlug());
			return $this->json($scout);
		}
     
	    //return $this->redirectToRoute('recherche_matricule_response',['slug'=>$scout->getSlug()]);
    }
}
