<?php
	
	namespace App\Utilities;
	
	use App\Entity\Adherant;
	use App\Entity\Sygesca3\Fonctions;
	use App\Entity\Sygesca3\Groupe;
	use App\Entity\Sygesca3\Region;
	use Doctrine\ORM\EntityManagerInterface;
	
	class GestionAdhesion
	{
		
		private $_em;
		
		public function __construct(EntityManagerInterface $entityManager)
		{
			$this->_em = $entityManager;
		}
		
		public function formulaire($request): array
		{
			
			$adherant = [
				'nom' => strtoupper($this->validForm($request->get('scout_nom'))),
				'prenoms' => strtoupper($this->validForm($request->get('scout_prenoms'))),
				'date_naissance' => $this->validForm($request->get('scout_date_naissance')),
				'lieu_naissance' => $this->validForm($request->get('scout_lieu_naissance')),
				'sexe' => $this->validForm($request->get('scout_sexe')),
				'contact' => $this->validForm($request->get('scout_contact')),
				'urgence' => $this->validForm($request->get('scout_urgence')),
				'contact_urgence' => $this->validForm($request->get('scout_contact_parent')),
				'old_id' => $this->validForm($request->get('scout_id')),
				'matricule' => $this->validForm($request->get('scout_matricule')),
				'fonction' => $this->_em->getRepository(Fonctions::class)->findOneBy(['id'=>$this->validForm($request->get('scout_fonction'))]),
				'groupe' => $this->_em->getRepository(Groupe::class)->findOneBy(['id'=>$this->validForm($request->get('scout_groupe'))]),
				'slug' => $this->validForm($request->get('scout_slug')),
				'branche' => $this->validForm($request->get('scout_branche')),
			];
			
			// Verification de l'existence de l'adherant dans le système
			$verifAdherant = $this->_em->getRepository(Adherant::class)->findOneBy([
				'nom' => $adherant['nom'],
				'prenoms' => $adherant['prenoms'],
				'dateNaissance' => $adherant['date_naissance'],
				'lieuNaissance' => $adherant['lieu_naissance'],
				'contact' => $adherant['contact'],
				'contactUrgence' => $adherant['contact_urgence']
			]);
			
			$id_transaction = time().''.substr(uniqid("",true), -9, 9);
			$status_paiement = "UNKNOW";
			
			// Si l'adherent n'existe pas alors enregistrer le dans le système
			// Sinon la mise a jour de ses informations
			if (!$verifAdherant){
				$scout = new Adherant();
				$scout->setMatricule($adherant['matricule']);
				$scout->setNom($adherant['nom']);
				$scout->setPrenoms($adherant['prenoms']);
				$scout->setDateNaissance($adherant['date_naissance']);
				$scout->setLieuNaissance($adherant['lieu_naissance']);
				$scout->setSexe($adherant['sexe']);
				$scout->setContact($adherant['contact']);
				$scout->setUrgence($adherant['urgence']);
				$scout->setContactUrgence($adherant['contact_urgence']);
				$scout->setBranche($adherant['branche']);
				$scout->setFonction($adherant['fonction']->getLibelle());
				$scout->setSlug($adherant['slug']);
				$scout->setOldId($adherant['old_id']);
				$scout->setIdTransaction($id_transaction);
				$scout->setStatusPaiement($status_paiement);
				$scout->setGroupe($adherant['groupe']);
				
				$this->_em->persist($scout);
				$this->_em->flush();
				
				$montant = $adherant['fonction']->getMontant();
				$am = (int) $montant/(1 - 0.035);
				$am = $this->arrondiSuperieur($am, 5);
				
				$message = [
					'id_transaction' => $id_transaction,
					'amount' => $am,
					'status' => true,
					'customer_id' => $scout->getId(),
					'customer_name' => $scout->getNom(),
					'customer_surname' => $scout->getPrenoms(),
					'description' => 'Adhesion de '.$scout->getNom().' '.$scout->getPrenoms()
				];
			}elseif ($verifAdherant->getStatusPaiement() !== 'VALID'){
				$montant = $verifAdherant->getMontant();
				$am = (int) $montant/(1 - 0.035);
				$am = $this->arrondiSuperieur($am, 5);
				
				$message = [
					'id_transaction' => $id_transaction,
					'amount' => $am,
					'status' => true,
					'customer_id' => $verifAdherant->getId(),
					'customer_name' => $verifAdherant->getNom(),
					'customer_surname' => $verifAdherant->getPrenoms(),
					'description' => 'Adhesion de '.$verifAdherant->getNom().' '.$verifAdherant->getPrenoms()
				];
			}else{
				$message = [
					'id_transaction' => null,
					'status' => false
				];
			}
			
			return $message;
		}
		
		/**
		 * Fonction pour arrondir au supérieur
		 *
		 * @param $nombre
		 * @param $arrondi
		 * @return float|int
		 */
		public function arrondiSuperieur($nombre, $arrondi)
		{
			return ceil($nombre / $arrondi) * $arrondi;
		}
		
		/**
		 * fonction verification des valeurs
		 *
		 * @param $donnee
		 * @return string
		 */
		public function validForm($donnee)
		{
			$result = htmlspecialchars(stripslashes(trim($donnee)));
			
			return $result;
		}
	}
