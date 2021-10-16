<?php
	
	namespace App\Controller;
	
	use App\Utilities\GestionAdhesion;
	use App\Utilities\GestionScout;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Serializer\Encoder\JsonEncoder;
	use Symfony\Component\Serializer\Encoder\XmlEncoder;
	use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
	use Symfony\Component\Serializer\Serializer;
	
	/**
	 * @Route("/adhesion")
	 */
	class AdhesionController extends AbstractController
	{
		private $_scout;
		private $_adhesion;
		
		public function __construct(GestionScout $gestionScout, GestionAdhesion $gestionAdhesion)
		{
			$this->_scout = $gestionScout;
			$this->_adhesion = $gestionAdhesion;
		}
		
		/**
		 * @Route("/", name="adhesion_ancien", methods={"GET","POST"})
		 */
		public function ancien(Request $request)
		{
			//Initialisation
			$encoders = [new XmlEncoder(), new JsonEncoder()];
			$normalizers = [new ObjectNormalizer()];
			$serializer = new Serializer($normalizers, $encoders);
			
			$message = $this->_adhesion->formulaire($request->request);
			
			return $this->json($message);
		}
	}
