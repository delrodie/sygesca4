<?php

namespace App\Controller;

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
     * @Route("/{nom}/{prenom}", name="recherche_identite_resultat", methods={"GET","POST"})
     */
    public function resultat(Request $request, $nom, $prenom): JsonResponse
    {


        //Initialisation
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $identite = [
            'nom' => $nom,
            'prenom' => $prenom
        ];

        return $this->json($identite);
    }
}
