<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\CompteType;
use App\Repository\CompteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sygesca/admin/compte")
 */
class SygescaAdminCompteController extends AbstractController
{
    /**
     * @Route("/", name="sygesca_admin_compte_index", methods={"GET","POST"})
     */
    public function index(Request $request, CompteRepository $compteRepository): Response
    {
	    $compte = new Compte();
	    $form = $this->createForm(CompteType::class, $compte);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->persist($compte);
		    $entityManager->flush();
		
		    return $this->redirectToRoute('sygesca_admin_compte_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('sygesca_admin_compte/index.html.twig', [
            'comptes' => $compteRepository->findBy([],['region'=>"ASC"]),
	        'compte' => $compte,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="sygesca_admin_compte_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $compte = new Compte();
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compte);
            $entityManager->flush();

            return $this->redirectToRoute('sygesca_admin_compte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_admin_compte/new.html.twig', [
            'compte' => $compte,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_admin_compte_show", methods={"GET"})
     */
    public function show(Compte $compte): Response
    {
        return $this->render('sygesca_admin_compte/show.html.twig', [
            'compte' => $compte,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sygesca_admin_compte_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Compte $compte, CompteRepository $compteRepository): Response
    {
        $form = $this->createForm(CompteType::class, $compte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sygesca_admin_compte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_admin_compte/edit.html.twig', [
	        'comptes' => $compteRepository->findBy([],['region'=>"ASC"]),
            'compte' => $compte,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_admin_compte_delete", methods={"POST"})
     */
    public function delete(Request $request, Compte $compte): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compte->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sygesca_admin_compte_index', [], Response::HTTP_SEE_OTHER);
    }
}
