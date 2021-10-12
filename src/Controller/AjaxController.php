<?php

namespace App\Controller;


use App\Entity\Sygesca3\Scout;
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
}
