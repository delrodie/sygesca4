<?php

namespace App\Controller\Backend;

use App\Entity\Sygesca3\District;
use App\Form\Sygesca3\DistrictType;
use App\Repository\DistrictReposiroty;
use App\Repository\RegionReposiroty;
use App\Utilities\Utility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sygesca/district")
 */
class SygescaDistrictController extends AbstractController
{
	private $regionReposiroty;
	private $utility;
	
	public function __construct(RegionReposiroty $regionReposiroty, Utility $utility)
	{
		$this->regionReposiroty = $regionReposiroty;
		$this->utility = $utility;
	}
    /**
     * @Route("/", name="sygesca_district_index", methods={"GET"})
     */
    public function index(Request  $request, DistrictReposiroty $districtReposiroty): Response
    {
		// Liste des district
	    $reqDistrict = $request->get('region');
		$districts = $this->utility->districtList($reqDistrict);
			
	    $district = new District();
	    $form = $this->createForm(DistrictType::class, $district);
	    $form->handleRequest($request);
	
	    if ($form->isSubmitted() && $form->isValid()) {
		    $entityManager = $this->getDoctrine()->getManager();
		    $entityManager->persist($district);
		    $entityManager->flush();
		
		    return $this->redirectToRoute('sygesca_district_index', [], Response::HTTP_SEE_OTHER);
	    }
		
        return $this->renderForm('sygesca_district/index.html.twig', [
            'districts' => $districts,
	        'regions' => $this->regionReposiroty->findListActive(),
	        'district' => $district,
	        'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="sygesca_district_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $district = new District();
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($district);
            $entityManager->flush();

            return $this->redirectToRoute('sygesca_district_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_district/new.html.twig', [
            'district' => $district,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_district_show", methods={"GET"})
     */
    public function show(District $district): Response
    {
        return $this->render('sygesca_district/show.html.twig', [
            'district' => $district,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sygesca_district_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, District $district): Response
    {
        $form = $this->createForm(DistrictType::class, $district);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sygesca_district_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sygesca_district/edit.html.twig', [
            'district' => $district,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="sygesca_district_delete", methods={"POST"})
     */
    public function delete(Request $request, District $district): Response
    {
        if ($this->isCsrfTokenValid('delete'.$district->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($district);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sygesca_district_index', [], Response::HTTP_SEE_OTHER);
    }
}
