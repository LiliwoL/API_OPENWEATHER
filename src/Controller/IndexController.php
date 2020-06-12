<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\HistoryRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="HomePage")
     */
    public function index( HistoryRepository $historyRepository )
    {
        // On va chercher la liste des dernières recherches
        $liste = $historyRepository->findAll();
        // $liste est un ARRAY d'entité       

        return $this->render('index/index.html.twig', 
        [
            'controller_name' => 'IndexController',
            // Tableau des dernières recherches
            'historique' => $liste
        ]);
    }
}
