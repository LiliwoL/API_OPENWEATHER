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

 