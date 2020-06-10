# Projet OpenWEATHER MAP


## Création de la homepage

```bash
php bin/console make:controller

 Choose a name for your controller class (e.g. DeliciousKangarooController):
 > IndexController

 created: src/Controller/IndexController.php
 created: templates/index/index.html.twig


  Success!


 Next: Open your new controller class and add some pages!
````

On donne le nom **IndexController** et deux fichiers sont créés.


## Voir les routes disponibles

```bash
➜  API_OPENWEATHER git:(master) ✗ php bin/console debug:router
 -------------------------- -------- -------- ------ -----------------------------------
  Name                       Method   Scheme   Host   Path
 -------------------------- -------- -------- ------ -----------------------------------
  _preview_error             ANY      ANY      ANY    /_error/{code}.{_format}
  _wdt                       ANY      ANY      ANY    /_wdt/{token}
  _profiler_home             ANY      ANY      ANY    /_profiler/
  _profiler_search           ANY      ANY      ANY    /_profiler/search
  _profiler_search_bar       ANY      ANY      ANY    /_profiler/search_bar
  _profiler_phpinfo          ANY      ANY      ANY    /_profiler/phpinfo
  _profiler_search_results   ANY      ANY      ANY    /_profiler/{token}/search/results
  _profiler_open_file        ANY      ANY      ANY    /_profiler/open
  _profiler                  ANY      ANY      ANY    /_profiler/{token}
  _profiler_router           ANY      ANY      ANY    /_profiler/{token}/router
  _profiler_exception        ANY      ANY      ANY    /_profiler/{token}/exception
  _profiler_exception_css    ANY      ANY      ANY    /_profiler/{token}/exception.css
  index                      ANY      ANY      ANY    /index
 -------------------------- -------- -------- ------ -----------------------------------
 ```

 On voit tout en bas, une nouvelle route **index** a été ajoutée.

 ## Contrôleur IndexController

 On teste la route URL/index.

 En cas d'erreur, il faut installer la dépendance **apache-pack**



 ```bash
➜  API_OPENWEATHER git:(master) ✗ composer require symfony/apache-pack
Using version ^1.0 for symfony/apache-pack
./composer.json has been updated
Loading composer repositories with package information
Updating dependencies (including require-dev)
Restricting packages listed in "symfony/symfony" to "5.1.*"
Package operations: 1 install, 0 updates, 0 removals
  - Installing symfony/apache-pack (v1.0.1): Downloading (100%)
Package zendframework/zend-code is abandoned, you should avoid using it. Use laminas/laminas-code instead.
Package zendframework/zend-eventmanager is abandoned, you should avoid using it. Use laminas/laminas-eventmanager instead.
Writing lock file
Generating optimized autoload files
ocramius/package-versions: Generating version class...
ocramius/package-versions: ...done generating version class
Symfony operations: 1 recipe (a3408b291625e5f9c5d7bbb30b47b38e)
  -  WARNING  symfony/apache-pack (>=1.0): From github.com/symfony/recipes-contrib:master
    The recipe for this package comes from the "contrib" repository, which is open to community contributions.
    Review the recipe at https://github.com/symfony/recipes-contrib/tree/master/symfony/apache-pack/1.0

    Do you want to execute this recipe?
    [y] Yes
    [n] No
    [a] Yes for all packages, only for the current installation session
    [p] Yes permanently, never ask again for this project
    (defaults to n): y
  - Configuring symfony/apache-pack (>=1.0): From github.com/symfony/recipes-contrib:master
Executing script cache:clear [OK]
Executing script assets:install public [OK]

Some files may have been created or updated to configure your new packages.
Please review, edit and commit them: these files are yours.
```

## Contrôleur WeatherController

Ce contrôleur permetrra la requête auprès de l'API

```bash
➜  API_OPENWEATHER git:(master) ✗ php bin/console make:controller

 Choose a name for your controller class (e.g. GrumpyChefController):
 > WeatherController

 created: src/Controller/WeatherController.php
 created: templates/weather/index.html.twig


  Success!


 Next: Open your new controller class and add some pages!
```

### On veut une route d'appel avec un paramètre dans l'url

Du genre:
**URL/query/NOM_DE_LA_VILLE**

Pour faire une requête auprès d'un autre site on va utiliser **CURL**.
Et pour ça on va crééer une méthode spécifique.

```php
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
```