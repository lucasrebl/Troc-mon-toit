<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class homeController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function home()
    {
        session_start();
        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // get all logements from table logements
        if (isset($_POST['search'])) {
            $cityLogements_ = $_POST['cityLogements'];
            $stmt4 = $dsn->prepare("SELECT * FROM logements WHERE city LIKE :city");
            $stmt4->bindValue(':city', "%$cityLogements_%");
            $stmt4->execute();
            $result = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt = $dsn->prepare("SELECT * FROM logements");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        foreach ($result as &$logement) {
            // check if the image exist
            if ($logement['image'] !== null) {
                // encode the image on base64 to display the image
                $logement['image'] = base64_encode($logement['image']);
            } else {
                // image is a caracter string NULL
                $logement['image'] = '';
            }
        }

        echo $this->twig->render('home/home.html.twig', ['logementsDetails' => $result]);
        // close the session 
        if (isset($_POST['logOut'])) {
            session_unset();
            session_destroy();
            echo '<script>window.location.replace("/login");</script>';
        }
    }
}
