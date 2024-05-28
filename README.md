# Troc mon toit

Ce projet est un site qui permet de réserver des logements

# Technologie

  - PHP
  - HTML/Twig
  - CSS/JS
  - MySQL
  - Docker

# Prerequisites

  - Avoir docker d'installer
  - Avoir un ide tel que (vs code, PHPStorm...)

# Installation

  - Première étape: clone le repository
    ```bash
    https://github.com/lucasrebl/Troc-mon-toit.git
    ```

  - Deuxième étape: Cliquer sur le lien suivant pour télécharger les fichier nécessaire au lancement du projet
      les fchiers à télécharger sont :
        - Dockerfile
        - docker-compose.yaml
        - .htaccess
    
      https://github.com/lucasrebl/Troc-mon-toit/releases/tag/file

      Attention le fichier .htaccess est nommé 'default.htaccess',
      une fois télécharger renommé le juste '.htaccess' sinon cela ne fonctionnera pas.

  - Troisième étape: Ouvrer le projet dans un ide puis placé les 2 fichiers docker à la raçine du projet

  - Quatrième étape: Lancer docker et éxecuter la commande suivant
    ```bash
    docker compose up -d --build
    ```

    puis cette commande
    ```bash
    sudo chmod -R 777 src
    ```
    cette commande vous demanderz votre mot de passe
   
  - Cinquième étape: dans le dossier src placé le fichier .htaccess télécharger précédemment 
    
  - Sixième étape: Aller sur ce lien pour voir le site
    
    http://localhost:8080/

# Contributor

Lucas Reboulet

# Contact

Si vous avez une question ou un probleme n'hésitez pas à me contacter à l'adresse mail suivant: rebouletlucas@gmail.com
