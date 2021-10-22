<?php

namespace App\Controller;


use App\Entity\Membre;
use App\Entity\Sygesca3\District;
use App\Entity\Sygesca3\Fonctions;
use App\Entity\Sygesca3\Groupe;
use App\Entity\Sygesca3\Region;
use App\Entity\Sygesca3\Scout;
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
 * @Route("/ajax")
 */
class AjaxController extends AbstractController
{

    private $gestionScout;
	private $gestionAdhesion;
	
	public function __construct(GestionScout $gestionScout, GestionAdhesion $gestionAdhesion)
    {
        $this->gestionScout = $gestionScout;
	    $this->gestionAdhesion = $gestionAdhesion;
    }

    /**
     * @Route("/{matricule}", name="requete_ajax_matricule", methods={"GET","POST"})
     */
    public function index(Request $request, $matricule): Response
    {
        //Initialisation
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $scout = $this->getDoctrine()->getRepository(Scout::class, 'sygesca')->findOneBy(['matricule'=>$matricule]);

        $data= $scout;

        return $this->json($data);
    }
    
    /**
     * @Route("/requete/formulaire", name="requete_ajax_formulaire", methods={"GET","POSt"})
     */
    public function formualire(Request $request)
    {
        //Initialisation
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $field = $request->get('field');
        $value = $request->get('value');

        if ($field === 'region'){
            $districts = $this->getDoctrine()->getRepository(District::class)->findBy(['region' => $value],['nom'=>"ASC"]);
            $data = $this->json($districts);
        }elseif ($field === 'district'){
            $groupes = $this->getDoctrine()->getRepository(Groupe::class)->findBy(['district' => $value],['paroisse'=>"ASC"]);
            $data = $this->json($groupes);
        }elseif ($field === 'fonction'){
            $fonction = $this->getDoctrine()->getRepository(Fonctions::class)->findOneBy(['id' => $value])->getMontant();
            $result = (int)$fonction / (1 - 0.035);
            $result = $this->gestionAdhesion->arrondiSuperieur($result, 5);
            $data = $this->json($result);
        }elseif ($field === 'regionIntialisation'){
			$regions = $this->getDoctrine()->getRepository(Region::class)->findAll();
			$data = $this->json($regions);
        }
		else{
            $data = $this->json([]);
        }

        return $data;

    }
	
	/**
	 * @Route("/recherche/badge/{matricule}", name="recherche_ajax_badge", methods={"GET"})
	 */
	public function badge(Request $request, $matricule)
	{
		//Initialisation
		$encoders = [new XmlEncoder(), new JsonEncoder()];
		$normalizers = [new ObjectNormalizer()];
		$serializer = new Serializer($normalizers, $encoders);
		
		$membre = $this->getDoctrine()->getRepository(Membre::class)->findOneBy(['matricule'=>$matricule]);
		
		if ($membre) $request->getSession()->set('matricule', $matricule);
		
		return $this->json($membre);
	}
}
