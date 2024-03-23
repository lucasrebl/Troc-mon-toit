<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class loginController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }

    public function login()
    {
        session_start();
        echo $this->twig->render('login/login.html.twig');
        if (isset($_POST['submit'])) {
            $login_email = $_POST['email'];
            $login_password = $_POST['passwordUser'];
            if (empty($login_email) || empty($login_password)) {
                echo "rempli tous les champs";
            }

            include __DIR__ . '/../models/loginModel.php';
            loginUser($login_email, $login_password);
        }
    }
}
