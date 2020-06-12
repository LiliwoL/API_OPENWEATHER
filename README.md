# Projet OpenWEATHER MAP



## Monter son dossier en SSHFS avec un script

```bash
#! /bin/sh

# Le dossier LOCAL
LOCAL_DIR=/Users/niko/www/formation/CESI/DEV18.NTE.OVH/
# PORT par défaut du SSH
PORT=22
# Nom d'utilisateur
USER=intervenant
# Adresse du serveur
HOST=dev18.nte.ovh
# Répertoire sur le serveur
REMOTE_DIR=/home/intervenant/web/intervenant.dev18.nte.ovh/public_html/

#IDENTITY_FILE="/Users/niko/.ssh/id_rsa.pub"


export PATH=/usr/local/bin/:$PATH

# Test si le dossier local existe
# Sinon, il est créé
if [ ! -d $LOCAL_DIR ]; then
    echo "$LOCAL_DIR n'existe pas!"
    mkdir $LOCAL_DIR
fi

# DéMontage
echo "Démontage"
sudo umount -f $LOCAL_DIR


# Montage
echo "Montage du répertoire"
# Debug
# -o debug,sshfs_debug,loglevel=debug
sshfs -o allow_other,auto_cache,defer_permissions,noappledouble,negative_vncache,reconnect,transform_symlinks,follow_symlinks,volname=DEV18.NTE.OVH -C -p $PORT $USER@$HOST:$REMOTE_DIR $LOCAL_DIR > /Users/niko/Scripts/mountDEV18.log

# Ouverture du répertoire avec Finder
open $LOCAL_DIR

# Retire et Ajoute dans les favoris
mysides remove DEV18.NTE.OVH
mysides add DEV18.NTE.OVH file://$LOCAL_DIR
```




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

On va donc travailler dans le fichier

**src/Controller/WeatherController.php**

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

On va ajouter une **action** du contrôleur qui prendre un paramètre dans l'url:

```php
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

        return $this->render(
            'weather/query.html.twig', 
            [
                'cityName' => $cityName,
                'controller_name' => 'WeatherController',
            ]
        );
    }
```

Et le **template** recevra le paramètre **cityName**
```twig
<h2>Nom de la ville donnée: {{ cityName }}! ✅</h2>
```

## Connexion à la base de données

On doit modifier le fichier **.env** avec les informations de la base.

```
DATABASE_URL=mysql://intervenant_formation_php:MOTDEPASSE@localhost:3306/intervenant_formation_php?serverVersion=5.7
```

Pour vérifier que l'application Symfony et la bibliothèque Doctrine a accès à la base:

```bash
php bin/console doctrine:schema:validate
```

### Création d'une entité

Une entité est une représentation OBJET d'une table de la base de données

```bash
php bin/console make:entity History
````

Et on ajoute les champs que l'on souhaite


### Ajout d'un nouvel enregistrement dans la base

Dans le fichier **WeatherController.php**, on va faire une nouvelle instance de l'entité **History** comme suit:

```php
...
        // Test du résultat
        if ( $resultat['cod'] == "200" )
        {
            // Ajoute dans la base cette recherche

            // On crée une nouvelle entité History
            // Pensez bien au use en haut du fichier!
            // use App\Entity\History;
            $history = new History();

            // On utilise le setter setQuery pour définir le terme recherché
            $history->setQuery($cityName);
            // Idem pour la date
            $history->setDate(new \DateTime());

            var_dump( $history );
            die;

            // Sauvegarder dans la base


            // On enverra le résultat au moteur de template pour affichage
            return $this->render(
                ...
```



### Synchronisation avec la base de données

On va taper les commandes suivantes dans le temrinal:

```bash
➜  API_OPENWEATHER git:(master) ✗ php bin/console make:migration 

           
  Success! 
           

 Next: Review the new migration "src/Migrations/Version20200612123511.php"
 Then: Run the migration with php bin/console doctrine:migrations:migrate
 See https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
```

Ca va nous générer un **fichier de migration** contenant toutes les choses à créer ou mettre à jour dans la base.

Et on execute la moigration:

```bash
php bin/console doctrine:migrations:migrate
```

Et là, la base et le code sont synchronisés!


#### Autre exemple avec une entité Memo

Une table avec les champs suivants:
* nom de la ville
* memo (quelqueq lignes)
* date

##### On crée l'entité correspondante

```bash
➜  API_OPENWEATHER git:(master) ✗ php bin/console make:entity

 Class name of the entity to create or update (e.g. GentleKangaroo):
 > Memo

 created: src/Entity/Memo.php
 created: src/Repository/MemoRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > cityName

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Memo.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > memo

 Field type (enter ? to see all types) [string]:
 > ?

Main types
  * string
  * text
  * boolean
  * integer (or smallint, bigint)
  * float

Relationships / Associations
  * relation (a wizard 🧙 will help you build the relation)
  * ManyToOne
  * OneToMany
  * ManyToMany
  * OneToOne

Array/Object Types
  * array (or simple_array)
  * json
  * object
  * binary
  * blob

Date/Time Types
  * datetime (or datetime_immutable)
  * datetimetz (or datetimetz_immutable)
  * date (or date_immutable)
  * time (or time_immutable)
  * dateinterval

Other Types
  * decimal
  * guid
  * json_array


 Field type (enter ? to see all types) [string]:
 > text

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Memo.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > date

 Field type (enter ? to see all types) [string]:
 > datetime

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Memo.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
 ```
On peut générer un CRUD (Create Read Update Delete) rapidement avec:

```bash
php bin/console make:crud Memo

 created: src/Controller/MemoController.php
 created: src/Form/MemoType.php
 created: templates/memo/_delete_form.html.twig
 created: templates/memo/_form.html.twig
 created: templates/memo/edit.html.twig
 created: templates/memo/index.html.twig
 created: templates/memo/new.html.twig
 created: templates/memo/show.html.twig

           
  Success! 
           

 Next: Check your new CRUD by going to /memo/
 ```
 
