# PrGoSfad

* Installer composer
* Mettre en place l'autoloading
* Installer les packages Whoops et Php-cs-fixer avec composer et en utilisant le flag --dev
* Installer twig avec composer

* Vérifier que le path de php.exe est bien dans les variables d'environnement https://www.php.net/manual/fr/faq.installation.php#faq.installation.addtopath
* Pour lancer le serveur PHP depuis la racine du projet => php -S localhost:8000 -t public

* Avant chaque commit faire un vendor/bin/php-cs-fixer fix --diff