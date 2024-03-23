<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class favoritesController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function favorites()
    {
        session_start();
        // get the user id from the session

        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $user = [
            'id' => $_SESSION['id'],
        ];
        // check if the user connect 
        if ($user['id'] > 0) {
            // condition add to favorites
            if (isset($_POST['submit'])) {
                // check if $nameLogements_ is not empty
                $nameLogements_ = $_POST['nameLogements'];
                $userId_ = $user['id'];
                include __DIR__ . '/../models/favoritesModel.php';
                addLogementsToFavorites($nameLogements_, $userId_);
                echo $this->twig->render('favorites/favorites.html.twig');
                if (empty($nameLogements_)) {
                    echo "ajout impossible veuiller remplir le champ ou nom du logements incorect ou logements inexistant";
                    echo $this->twig->render('favorites/favorites.html.twig');
                }
            } else if (isset($_POST['submit2'])) { //condition delete a favorites
                    $nameLogements_ = $_POST['nameLogements'];
                    $userId_ = $user['id'];
                    include __DIR__ . '/../models/favoritesModel.php';
                    deleteLogementsToFavorites($nameLogements_, $userId_);
                    echo $this->twig->render('favorites/favorites.html.twig');
                    if (empty($nameLogements_)) {
                        // check if $nameLogements_ is not empty
                        echo "suppression impossible veuiller remplir le champ ou nom du logements incorect ou logements inexistant";
                        echo $this->twig->render('favorites/favorites.html.twig');
                    } 
            }
        } else {
            echo "veuiller vous connecté pour mettre un logements en favoris ou le supprimer";
        }
    }
}
