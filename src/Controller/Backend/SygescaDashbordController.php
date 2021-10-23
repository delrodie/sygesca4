<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sygesca")
 */
class SygescaDashbordController extends AbstractController
{
    /**
     * @Route("/", name="sygesca_dashbord")
     */
    public function index(): Response
    {
        return $this->render('sygesca_dashbord/index.html.twig', [
            'controller_name' => 'SygescaDashbordController',
        ]);
    }
}
