<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    /**
     * @Route("/weather", name="weather")
     */
    public function index()
    {
        return $this->render('weather/index.html.twig', [
            'controller_name' => 'WeatherController',
        ]);
    }
}
