<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="HomePage")
     */
    public function index()
    {
        // On va chercher la liste des dernières recherches
        $liste = ["Rome", "New York", "Ouahigouya"];

        return $this->render('index/index.html.twig', 
        [
            'controller_name' => 'IndexController',
            // Tableau des dernières recherches
            'historique' => $liste
        ]);
    }
}
