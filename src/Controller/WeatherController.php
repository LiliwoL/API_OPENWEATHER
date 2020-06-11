<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    /**
     * Action pour rechercher le temps pour une ville donée
     * 
     * @Route(
     *  "/query/{cityName}",
     *  name="Query Weather to a given city"
     * )
     */
    public function query( $cityName )
    {
        // A partir du paramètre on va faire appel à l'API

        // Construire l'URL à appeler
        $url = "http://api.openweathermap.org/data/2.5/weather?lang=fr&units=metric&appid=ca18014071190091d4be752b98e34330&q=" . $cityName;

        // Utilisation de la méthode makeRequest avec l'url qu'on vient de construire
        $resultat = $this->makeRequest( $url );

        // Test du résultat
        //var_dump($resultat['cod']);
        //die;

        // Test du résultat
        if ( $resultat['cod'] == "200" )
        {
            // On enverra le résultat au moteur de template pour affichage
            return $this->render(
                'weather/query.html.twig', 
                [
                    'cityName' => $cityName,
                    'resultat' => $resultat
                ]
            );
        }else{
            // Non trouvé
            // On enverra le résultat au moteur de template pour affichage
            return $this->render(
                'error/error.html.twig', 
                [
                    'cityName' => $cityName,
                    'message' => $resultat['message']
                ]
            );
        }
        
    }

    /**
     * Fonction qui exécutera la requete en cURL
     *
     * @param string $url
     * @return array
     */
    private function makeRequest ( string $url )
    {
        // Initialisation de cURL
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Au cas où on a un souci avec le SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);

        // Execute
        $result=curl_exec($ch);

        // En cas d'erreur
        if ( $result === false )
        {
            // Affichage de l'erreur
            dump ( curl_error($ch) );
        }

        // Closing
        curl_close($ch);

        // Decodage du JSON reçu
        $data = json_decode($result, true);

        // Renvoi du tableau JSON
        return (array) $data;
    }
}
