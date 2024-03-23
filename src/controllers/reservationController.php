<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class reservationController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function reservation()
    {
        session_start();
        $user = [
            'id' => $_SESSION['id'],
        ];
        if ($user['id'] < 1) {
            echo '<script>window.location.replace("/");</script>';
        }
        $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
        $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $dsn->prepare("SELECT * FROM booking");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (isset($_POST['submit'])) {
            $name_ = $_POST['name'];
            $startDate_ = $_POST['startDate'];
            $endDate_ = $_POST['endDate'];
            $userId_ = $user['id'];
            include __DIR__ . '/../models/reservationModel.php';
            reservLogements($name_, $startDate_, $endDate_, $userId_);
        }

        echo $this->twig->render('reservation/reservation.html.twig', ['bookingDetails' => $result]);
    }
}
