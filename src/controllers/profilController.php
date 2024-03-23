<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class profilController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function profil()
    {
        session_start();

        // get the user id from the session
        $user = [
            'id' => $_SESSION['id']
        ];
        $userId_ = $user['id'];

        if ($user['id'] < 1) {
            echo "veuiller vous connecter ou créer un compte pour avoir accés a votre profil";
        }

        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // request sql SELECT all data FROM user 
        $stmt = $dsn->prepare("SELECT * FROM user WHERE id=:idUser");
        $stmt->bindParam(':idUser', $user['id']);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // request sql SELECT all data FROM favorites 
        $stmt2 = $dsn->prepare("SELECT DISTINCT * FROM favorites WHERE id_user=:idUser");
        $stmt2->bindParam(':idUser', $user['id']);
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        // request sql SELECT all data FROM booking 
        $stmt3 = $dsn->prepare("SELECT * FROM booking WHERE id_user=:userId");
        $stmt3->bindParam(':userId', $userId_);
        $stmt3->execute();
        $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $comment = $dsn->prepare("SELECT * FROM notesComments WHERE id_user=:userId_");
        $comment->bindParam(':userId_', $userId_, PDO::PARAM_INT);
        $comment->execute();
        $result4 = $comment->fetchAll(PDO::FETCH_ASSOC);


        echo $this->twig->render('profil/profil.html.twig', [
            'userDetails' => $result,
            'favoritesDetails' => $result2,
            'bookingDetails' => $result3,
            'notesCommentsDetails' => $result4
        ]);
        if (isset($_POST['logOut'])) {
            session_unset();
            session_unset();
            echo '<script>window.location.replace("/login");</script>';
        }
    }
}
