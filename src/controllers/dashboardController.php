<?php

require 'vendor/autoload.php';
include __DIR__ . '/../models/connect.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class dashboardController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function dashboard()
    {
        session_start();
        $user = [
            'isAdmin' => $_SESSION['isAdmin'],
        ];

        if ($user['isAdmin'] == 0) {
            echo '<script>window.location.replace("/");</script>';
        }
        // print_r($_SESSION);
        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $dsn->prepare("SELECT * FROM equipements");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // 
        $stmt2 = $dsn->prepare("SELECT * FROM services");
        $stmt2->execute();
        $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        // 
        $stmt3 = $dsn->prepare("SELECT * FROM logementsType");
        $stmt3->execute();
        $result3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        // 
        if (isset($_POST['search'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $stmt4 = $dsn->prepare("SELECT * FROM logements WHERE name LIKE :name");
            $stmt4->bindValue(':name', "%$nameLogements_%");
            $stmt4->execute();
            $result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $stmt4 = $dsn->prepare("SELECT * FROM logements");
            $stmt4->execute();
            $result4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
        }
        // 
        $stmt5 = $dsn->prepare("SELECT * FROM equipementsLogements");
        $stmt5->execute();
        $result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        //
        $stmt6 = $dsn->prepare("SELECT * FROM servicesLogements");
        $stmt6->execute();
        $result6 = $stmt6->fetchAll(PDO::FETCH_ASSOC);
        // 
        $stmt7 = $dsn->prepare("SELECT * FROM logementsTypeLogements");
        $stmt7->execute();
        $result7 = $stmt7->fetchAll(PDO::FETCH_ASSOC);
        // 
        $stmt8 = $dsn->prepare("SELECT * FROM user");
        $stmt8->execute();
        $result8 = $stmt8->fetchAll(PDO::FETCH_ASSOC);
        // 
        $stmt9 = $dsn->prepare("SELECT * FROM notesComments");
        $stmt9->execute();
        $result9 = $stmt9->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result4 as &$logement) {
            if ($logement['image'] !== null) {
                $logement['image'] = base64_encode($logement['image']);
            } else {
                $logement['image'] = '';
            }
        }
        echo $this->twig->render('dashboard/dashboard.html.twig', [
            'equipementsDetails' => $result,
            'servicesDetails' => $result2,
            'logementsTypeDetails' => $result3,
            'logementsDetails' => $result4,
            'equipementsLogementsDetails' => $result5,
            'servicesLogementsDetails' => $result6,
            'logementsTypeLogementsDetails' => $result7,
            'userDetails' => $result8,
            'notesCommentsDetails' => $result9
        ]);

        if (isset($_POST['logOut'])) {
            session_unset();
            session_unset();
            echo '<script>window.location.replace("/login");</script>';
        }

        // condition add logements
        if (isset($_POST['submit'])) {
            $price_ = $_POST['price'];
            $city_ = $_POST['city'];
            $name_ = $_POST['name'];
            if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
                $imageContent = file_get_contents($_FILES["image"]["tmp_name"]);
                include __DIR__ . '/../models/dashboardModel.php';
                insertLogements($price_, $city_, $name_, $imageContent);
            } else {
                echo "Erreur lors du téléchargement de l'image.";
            }
        }

        // condition add services to logements
        else if (isset($_POST['submit2'])) {
            $logementsNameSelect_ = $_POST['logementsNameSelect'];
            $addServicesToLogements_ = $_POST['addServicesToLogements'];
            include __DIR__ . '/../models/dashboardModel.php';
            insertServicesToLogement($logementsNameSelect_, $addServicesToLogements_);
        }

        // condition add equipements to logements
        else if (isset($_POST['submit3'])) {
            $logementsNameSelect_ = $_POST['logementsNameSelect'];
            $addEquipementsToLogements_ = $_POST['addEquipementsToLogements'];
            include __DIR__ . '/../models/dashboardModel.php';
            insertEquipementsToLogement($logementsNameSelect_, $addEquipementsToLogements_);
        }

        // condition add logementsType to loggements
        else if (isset($_POST['submit4'])) {
            $logementsNameSelect_ = $_POST['logementsNameSelect'];
            $addLogementTypeToLogements_ = $_POST['addLogementTypeToLogements'];
            include __DIR__ . '/../models/dashboardModel.php';
            insertLogementsTypeToLogement($logementsNameSelect_, $addLogementTypeToLogements_);
        }

        // condition add equipements
        if (isset($_POST['submit5'])) {
            $nameEquipements_ = $_POST['nameEquipements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            inserEquipements($nameEquipements_);
        }
        // condition add services
        else if (isset($_POST['submit6'])) {
            $nameServices_ = $_POST['nameServices'];
            include __DIR__ .  '/../models/dashboardModel.php';
            insertServices($nameServices_);
        }
        // condition add logements type
        else if (isset($_POST['submit7'])) {
            $nameLogementsType_ = $_POST['nameLogementsType'];
            include __DIR__ .  '/../models/dashboardModel.php';
            insertLogementsType($nameLogementsType_);
        }
        // condition delete logements
        else if (isset($_POST['delete'])) {
            $nameDelete_ = $_POST['nameDelete'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteLogements($nameDelete_);
        }
        // condition delete equipements
        else if (isset($_POST['delete2'])) {
            $nameEquipementsDelete_ = $_POST['nameEquipementsDelete'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteEquipements($nameEquipementsDelete_);
        }
        // condition delete services
        else if (isset($_POST['delete3'])) {
            $servicesDelete_ = $_POST['nameServicesDelete'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteServices($servicesDelete_);
        }
        // condition delete logements type
        else if (isset($_POST['delete4'])) {
            $nameLogementsTypeDelete_ = $_POST['nameLogementsTypeDelete'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteLogementsType($nameLogementsTypeDelete_);
        }
        // condition delete user
        else if (isset($_POST['delete5'])) {
            $userDelete_  = $_POST['userDelete'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteUSer($userDelete_);
        }
        // condition delete equipements in logements
        else if (isset($_POST['delete6'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $nameEquipementsDeleteInLogements_ = $_POST['nameEquipementsDeleteInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteEquipementsInLogements($nameLogements_, $nameEquipementsDeleteInLogements_);
        }
        // condition delete services in logements
        else if (isset($_POST['delete7'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $nameServicesDeleteInLogements_ = $_POST['nameServicesDeleteInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteServicesInLogements($nameLogements_, $nameServicesDeleteInLogements_);
        }
        // condition delete logements type in logements
        else if (isset($_POST['delete8'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $nameLogementsTypeDeleteInLogements_ = $_POST['nameLogementsTypeDeleteInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            deleteLogementsTypeInLogements($nameLogements_, $nameLogementsTypeDeleteInLogements_);
        }
        // condition update logements name
        else if (isset($_POST['update'])) {
            $nameSelect_ = $_POST['nameSelect'];
            $nameUpdate_ = $_POST['nameUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            upadteLogementsName($nameSelect_, $nameUpdate_);
        }
        // condition update logements price
        else if (isset($_POST['update2'])) {
            $nameSelect_ = $_POST['nameSelect'];
            $priceUpdate_ = $_POST['priceUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            upadteLogementsPrice($nameSelect_, $priceUpdate_);
        }
        // condition update logements city
        else if (isset($_POST['update3'])) {
            $nameSelect_ = $_POST['nameSelect'];
            $cityUpdate_ = $_POST['cityUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            upadteLogementsCity($nameSelect_, $cityUpdate_);
        }
        // condition update image
        else if (isset($_POST['update4'])) {
            $nameSelect_ = $_POST['nameSelect'];
            if (isset($_FILES["imageUpdate"]) && $_FILES["imageUpdate"]["error"] == UPLOAD_ERR_OK) {
                $imageContent = file_get_contents($_FILES["imageUpdate"]["tmp_name"]);
                include __DIR__ . '/../models/dashboardModel.php';
                updateLogementsImage($nameSelect_, $imageContent);
            } else {
                echo "Erreur lors du téléchargement de l'image.";
            }
        }
        // condition update equipements
        else if (isset($_POST['update5'])) {
            $nameEquipementsSelect_ = $_POST['nameEquipementsSelect'];
            $nameEquipementsUpdate_ = $_POST['nameEquipementsUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateEquipements($nameEquipementsSelect_, $nameEquipementsUpdate_);
        }
        // condition update services
        else if (isset($_POST['update6'])) {
            $nameServicesSelect_ = $_POST['nameServicesSelect'];
            $nameServicesUpdate_ = $_POST['nameServicesUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateServices($nameServicesSelect_, $nameServicesUpdate_);
        }
        // condition update logements type
        else if (isset($_POST['update7'])) {
            $logementsTypeSelect_ = $_POST['logementsTypeSelect'];
            $logementsTypeUpdate_ = $_POST['logementsTypeUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateLogementsType($logementsTypeSelect_, $logementsTypeUpdate_);
        }
        // condition update equipements in logements
        else if (isset($_POST['update8'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $nameEquipementsSelect_ = $_POST['nameEquipementsSelect'];
            $nameEquipementsUpdateInLogements_ = $_POST['nameEquipementsUpdateInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateEquipementsInLogements($nameLogements_, $nameEquipementsSelect_, $nameEquipementsUpdateInLogements_);
        }
        // condition update services in logements
        else if (isset($_POST['update9'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $nameServicesSelect_ = $_POST['nameServicesSelect'];
            $nameServicesUpdateInLogements_ = $_POST['nameServicesUpdateInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateServicesInLogements($nameLogements_, $nameServicesSelect_, $nameServicesUpdateInLogements_);
        }
        // condition update logements type in logements
        else if (isset($_POST['update10'])) {
            $nameLogements_ = $_POST['nameLogements'];
            $logementsTypeSelect_ = $_POST['logementsTypeSelect'];
            $logementsTypeUpdateInLogements_ = $_POST['logementsTypeUpdateInLogements'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateLogementsTypeInLogements($nameLogements_, $logementsTypeSelect_, $logementsTypeUpdateInLogements_);
        }
        // condition update user email
        else if (isset($_POST['update11'])) {
            $userEmailSelect_ = $_POST['userEmailSelect'];
            $userEmailUpdate_ = $_POST['userEmailUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateUserEmail($userEmailSelect_, $userEmailUpdate_);
        }
        // condition update user firstName
        else if (isset($_POST['update12'])) {
            $userEmailSelect_ = $_POST['userEmailSelect'];
            $userFirstNameUpdate_ = $_POST['userFirstNameUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateUserFirstName($userEmailSelect_, $userFirstNameUpdate_);
        }
        // condition update user lastName
        else if (isset($_POST['update13'])) {
            $userEmailSelect_ = $_POST['userEmailSelect'];
            $userLastNameUpdate_ = $_POST['userLastNameUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateUserLastName($userEmailSelect_, $userLastNameUpdate_);
        }
        // condition update user password
        else if (isset($_POST['update14'])) {
            $userEmailSelect_ = $_POST['userEmailSelect'];
            $userPasswordUSerUpdate_ = $_POST['userPasswordUSerUpdate'];
            $hashed_password_ = password_hash($userPasswordUSerUpdate_, PASSWORD_DEFAULT);

            include __DIR__ .  '/../models/dashboardModel.php';
            updateUserPassword($userEmailSelect_, $hashed_password_);
        }
        // condition update user phoneNumber
        else if (isset($_POST['update15'])) {
            $userEmailSelect_ = $_POST['userEmailSelect'];
            $userPhoneNumberUpdate_ = $_POST['userPhoneNumberUpdate'];
            include __DIR__ .  '/../models/dashboardModel.php';
            updateUserPhoneNumber($userEmailSelect_, $userPhoneNumberUpdate_);
        }
        // condition add notes and comments
        else if (isset($_POST['submit700'])) {
            $contentNotes_ = $_POST['contentNotes'];
            $contentComments_ = $_POST['contentComments'];
            $id_logements_ = $_POST['id_logements'];
            $id_user_ = $_POST['id_user'];
            include __DIR__ . '/../models/dashboardModel.php';
            insertNotesComments($contentNotes_, $contentComments_, $id_logements_, $id_user_);
        }
        // condition delete notes and comments
        else if (isset($_POST['delete700'])) {
            $id_logements_ = $_POST['id_logements'];
            $id_user_ = $_POST['id_user'];
            include __DIR__ . '/../models/dashboardModel.php';
            deleteNotesComments($id_logements_, $id_user_);
        }
        // condition update notes and comments
        else if (isset($_POST['update700'])) {
            $contentNotes_ = $_POST['contentNotes'];
            $contentComments_ = $_POST['contentComments'];
            $id_logements_ = $_POST['id_logements'];
            $id_user_ = $_POST['id_user'];
            include __DIR__ . '/../models/dashboardModel.php';
            updateNotesComments($contentNotes_, $contentComments_, $id_logements_, $id_user_);
        }
    }
}
