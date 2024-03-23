<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class logementsController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function logements()
    {
        session_start();
        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // set variable logementsId_ = specific id when user click on button "Voir les logements"
        $logementsId_ = isset($_GET['id']) ? $_GET['id'] : null;

        // check if logements exist
        if ($logementsId_ > 0) {
            // request sql for SELECT all data from table logements where id = $logementsId_
            $stmt = $dsn->prepare("SELECT * FROM logements WHERE id = :logementsId");
            $stmt->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // request sql for SELECT nameServices FROM table services whith join table servicesLogements where id = $logementsId_
            $stmt2 = $dsn->prepare("SELECT nameServices FROM services INNER JOIN servicesLogements ON services.id_services = servicesLogements.id_services WHERE id_logements = :logementsId");
            $stmt2->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $stmt2->execute();
            $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // request sql for SELECT nameEquipements FROM table equipements whith join table equipementsLogements where id = $logementsId_
            $stmt3 = $dsn->prepare("SELECT nameEquipements FROM equipements INNER JOIN equipementsLogements ON equipements.id_equipements = equipementsLogements.id_equipements WHERE id_logements = :logementsId");
            $stmt3->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $stmt3->execute();
            $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

            // request sql for SELECT nameLogementsType FROM table logementsType whith join table logementsTypeLogements where id = $logementsId_
            $stmt4 = $dsn->prepare("SELECT nameLogementsType FROM logementsType INNER JOIN logementsTypeLogements ON logementsType.id_logementsType = logementsTypeLogements.id_logementsType WHERE id_logements = :logementsId");
            $stmt4->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $stmt4->execute();
            $result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

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

            $checkReserv = $dsn->prepare("SELECT * FROM booking WHERE id_logements=:logementsId");
            $checkReserv->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $checkReserv->execute();
            $result5 = $checkReserv->fetchAll(PDO::FETCH_ASSOC);

            $comment = $dsn->prepare("SELECT * FROM notesComments WHERE id_logements=:logementsId");
            $comment->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $comment->execute();
            $result6 = $comment->fetchAll(PDO::FETCH_ASSOC);

            $averageRating = $dsn->prepare("SELECT AVG(contentNotes) AS averageRating FROM notesComments WHERE id_logements = :logementsId");
            $averageRating->bindParam(':logementsId', $logementsId_, PDO::PARAM_INT);
            $averageRating->execute();
            $averageRatingResult = $averageRating->fetch(PDO::FETCH_ASSOC);

            $averageRating = $averageRatingResult['averageRating'];
            echo "note moyenne du logements: $averageRating";


            echo $this->twig->render('logements/logements.html.twig', [
                'logementsDetails' => $result,
                'servicesDetails' => $result2,
                'equipementsDetails' => $result3,
                'logementsTypeDetails' => $result4,
                'bookingDetails' => $result5,
                'notesCommentsDetails' => $result6
            ]);
        } else {
            echo $this->twig->render('logements/logements.html.twig');
        }
    }
}
