<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Entity\Compteur;
use App\Entity\Membre;
use App\Entity\Sygesca3\Fonctions;
use App\Entity\Sygesca3\Region;
use App\Entity\Sygesca3\Scout;
use App\Entity\Sygesca3\Statut;
use App\Utilities\GestionAdhesion;
use App\Utilities\GestionCotisation;
use App\Utilities\GestionScout;
use CinetPay\CinetPay;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/cinetpay")
 */
class CinetpayController extends AbstractController
{
	private $_adhesion;
	private $_scout;
	private $_em;
	private $_cotisation;
	
	public function __construct(GestionAdhesion $gestionAdhesion, GestionScout $_scout, EntityManagerInterface $_em, GestionCotisation $_cotisation)
	{
		$this->_adhesion = $gestionAdhesion;
		$this->_scout = $_scout;
		$this->_em = $_em;
		$this->_cotisation = $_cotisation;
	}
    /**
     * @Route("/notify", name="cinetpay_notification")
     */
    public function index(Request $request): Response
    {
	    //Initialisation
	    $encoders = [new XmlEncoder(), new JsonEncoder()];
	    $normalizers = [new ObjectNormalizer()];
	    $serializer = new Serializer($normalizers, $encoders);
		
		// Initialisation des variables
	    $cpmTransId = $request->get('cpm_trans_id');
		
		if (isset($cpmTransId)){
			try {
				// Initialisation de CinetPay et Identification du paiement
				$id_transaction = $cpmTransId;
				$apiKey = '18714242495c8ba3f4cf6068.77597603';
				$site_id = 422630;
				$plateform = "PROD"; // Valorisé à PROD si vous êtes en production
				$CinetPay = new CinetPay($site_id, $apiKey, $plateform);
				// Reprise exacte des bonnes données chez CinetPay
				$CinetPay->setTransId($id_transaction)->getPayStatus();
				$cpm_site_id = $CinetPay->_cpm_site_id;
				$signature = $CinetPay->_signature;
				$cpm_amount = $CinetPay->_cpm_amount;
				$cpm_trans_id = $CinetPay->_cpm_trans_id;
				$cpm_custom = $CinetPay->_cpm_custom;
				$cpm_currency = $CinetPay->_cpm_currency;
				$cpm_payid = $CinetPay->_cpm_payid;
				$cpm_payment_date = $CinetPay->_cpm_payment_date;
				$cpm_payment_time = $CinetPay->_cpm_payment_time;
				$cpm_error_message = $CinetPay->_cpm_error_message;
				$payment_method = $CinetPay->_payment_method;
				$cpm_phone_prefixe = $CinetPay->_cpm_phone_prefixe;
				$cel_phone_num = $CinetPay->_cel_phone_num;
				$cpm_ipn_ack = $CinetPay->_cpm_ipn_ack;
				$created_at = $CinetPay->_created_at;
				$updated_at = $CinetPay->_updated_at;
				$cpm_result = $CinetPay->_cpm_result;
				$cpm_trans_status = $CinetPay->_cpm_trans_status;
				$cpm_designation = $CinetPay->_cpm_designation;
				$buyer_name = $CinetPay->_buyer_name;
				
				// Verification si l'opération de Cinetpay est effective
				if ($cpm_result === '00'){
					// Verification de l'opération dans la table ADHERANT
					$adherant = $this->getDoctrine()->getRepository(Adherant::class)->findOneBy(['idTransaction'=>$id_transaction]);
					if ($adherant->getResult() === '00'){
						$message = [
							'id_transaction' => $id_transaction,
							'status' => false,
							'matricule' => $adherant->getMatricule()
						];
					}else{
						//Initialisation des variables
						$fonction = $this->getDoctrine()->getRepository(Fonctions::class)->findOneBy(['libelle'=>$adherant->getFonction()]);
						
						if ($fonction->getId() < 5){
							$branche = $fonction->getLibelle();
							$scout_statut = $this->getDoctrine()->getRepository(Statut::class)->findOneBy(['id'=>1]);
						}else{
							$branche = $adherant->getBranche();
							$scout_statut = $this->getDoctrine()->getRepository(Statut::class)->findOneBy(['id'=>2]);
						}
						//dd($adherant);
						// Si le membre existe alors une mise a jour des informations
						// Sinon enregistrer le
						$data = $this->membre($adherant, $branche, $scout_statut); //dd($data['scout']);
						
						// Mise a jour de la carte du membre et enregistrement de la cotisation
						$this->_scout->carte($data['scout'], $data['region_code'], $data['id']);
						$this->_cotisation->save($data['scout'],$fonction, (int) $cpm_amount, $cel_phone_num);
						
						// Mise a jour de la table Adherant
						$adherant->setResult('00');
						$adherant->setStatusPaiement('VALID');
						$this->_em->flush();
						
						$session = $request->getSession();
						
						$session->set('matricule', $data['scout']->getMatricule());
						
						$message = [
							'id_transation' => $id_transaction,
							'matricule' => $data['scout']->getMatricule()
						];
						
						return $this->json($message);
					}
				}else{
					throw new \Exception("Une erreur en survenu sur votre inscription");
				}
			
			}catch (\Exception $e){
				echo "Erreur :". $e->getMessage();
			}
		}else{
			throw new \Exception("Echec! Page inaccessible.!");
		}
	
	
	    return $this->render('cinetpay/index.html.twig', [
            'controller_name' => 'CinetpayController',
        ]);
    }
	
	protected function membre(object $adherant, string $branche, object $scout_statut)
	{
		$scout = $this->getDoctrine()->getRepository(Membre::class)->findOneBy(['matricule'=>$adherant->getMatricule()]);
		$region = $this->getDoctrine()->getRepository(Region::class)->findOneBy(['id' => $adherant->getGroupe()->getDistrict()->getRegion()]);
		if (!$adherant->getMatricule()){ //die('ici');
			$scout = new Membre();
			
			// Matricule
			$matricule = $this->_scout->matricule($region->getCode());
			$slugify = new Slugify();
			$slug = $slugify->slugify($adherant->getNom().'-'.$adherant->getPrenoms().'-'.$matricule);
			
			// Nombre de scout
			$compteur = $this->getDoctrine()->getRepository(Compteur::class)->findAll();
			if (count($compteur) === 0){
				$nombre = count($this->getDoctrine()->getRepository(Scout::class, 'sygesca')->findAll()) + 1;
				$compteur = new Compteur();
				$compteur->setNombre((int) $nombre);
				$this->_em->persist($compteur);
				$this->_em->flush();
			}else{
				$nombre = (int) $compteur->getNombre() + 1;
				$compteur->setNombre($nombre);
				$this->_em->flush();
			}
			$id = $nombre;
		}elseif (!$scout){
			$scout = new Membre();
			$slug = $adherant->getSlug();
			$matricule = $adherant->getMatricule();
			$id = $adherant->getOldId();
		}else{
			$slug = $scout->getSlug();
			$matricule = $scout->getMatricule();
			$id = $adherant->getOldId();
		}
		$scout->setSlug($slug);
		$scout->setMatricule($matricule);
		$scout->setNom($adherant->getNom());
		$scout->setPrenoms($adherant->getPrenoms());
		$scout->setDateNaissance($adherant->getDateNaissance());
		$scout->setLieuNaissance($adherant->getLieuNaissance());
		$scout->setSexe($adherant->getSexe());
		$scout->setContact($adherant->getContact());
		$scout->setUrgence($adherant->getUrgence());
		$scout->setContactUrgence($adherant->getContactUrgence());
		$scout->setBranche($branche);
		$scout->setFonction($adherant->getFonction());
		$scout->setGroupe($adherant->getGroupe());
		$scout->setStatut($scout_statut);
		
		$this->_em->persist($scout);
		$this->_em->flush();
		
		return $data = [
			'scout' => $scout,
			'id' => $id,
			'region_code' => $region->getCode()
		];
	}
}
