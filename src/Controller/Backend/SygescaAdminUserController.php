<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sygesca/admin/user")
 */
class SygescaAdminUserController extends AbstractController
{
	private $passwordHasher;
	
	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}
	
    /**
     * @Route("/", name="sygesca_admin_user_index", methods={"GET","POST"})
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
	    $user = new User();
	    $form = $this->createForm(UserType::class, $user);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
			$user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
		    $entityManager->persist($user);
		    $entityManager->flush();
			
			$this->addFlash('success', "Utilisateur ajouté avec succès");
		
		    return $this->redirectToRoute('sygesca_admin_user_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('sygesca_admin_user/index.html.twig', [
            'users' => $userRepository->findWithoutDelrodie(),
	        'user' => $user,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="sygesca_admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('sygesca_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('sygesca_admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sygesca_admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
	        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            $this->getDoctrine()->getManager()->flush();
	
	        $this->addFlash('success', "Utilisateur ajouté avec succès");

            return $this->redirectToRoute('sygesca_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_admin_user/edit.html.twig', [
	        'users' => $userRepository->findWithoutDelrodie(),
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sygesca_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
