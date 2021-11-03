<?php
	
	namespace App\Utilities;
	
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Security\Core\Security;
	
	class GestionSecurity
	{
		private $_em;
		private $request;
		private $_security;
		private $passwordHasher;
		
		public function __construct(EntityManagerInterface $_em, RequestStack $request, Security $_security, UserPasswordHasherInterface $passwordHasher)
		{
			$this->_em = $_em;
			$this->request = $request;
			$this->_security = $_security;
			$this->passwordHasher = $passwordHasher;
		}
		
		/**
		 * Initialisation du compte utilisateur
		 *
		 * @return bool
		 */
		public function intialisation(): bool
		{
			$user = $this->_em->getRepository(User::class)->findOneBy(['username'=>'delrodie']);
			$result = false;
			if (!$user){
				$user = new User();
				$user->setEmail('delrodieamoikon@gmail.com');
				$user->setUsername('delrodie');
				$user->setPassword($this->passwordHasher->hashPassword($user, 'sygesca4'));
				$user->setRoles(['ROLE_SUPER_ADMIN']);
				$this->_em->persist($user);
				$this->_em->flush();
				
				$result = true;
			}
			
			return $result;
		}
		
		public function connexion(): bool
		{
			$user = $this->_em->getRepository(User::class)->findOneBy(['username'=>$this->_security->getUser()->getUserIdentifier()]);
			
			// Definition des variable
			$nombre_conneion = (int)$user->getLoginCount();
			
			$user->setLoginCount($nombre_conneion + 1);
			$user->setLastConnection(new \DateTime()); //dd($user);
			
			$this->_em->flush();
			
			return true;
		}
	}
