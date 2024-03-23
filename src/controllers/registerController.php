<?php

require 'vendor/autoload.php';


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class registerController
{
    protected $twig;
    private $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../views/templates');
        $this->twig = new Environment($this->loader);
    }


    public function dataRegister()
    {
        echo $this->twig->render('register/register.html.twig');
        if (isset($_POST['submit'])) {
            $first_Name = $_POST['firstName'];
            $last_Name = $_POST['lastName'];
            $email_ = $_POST['email'];
            $password_User = $_POST['passwordUser'];
            $phone_Number = $_POST['phoneNumber'];

            $hashed_password = password_hash($password_User, PASSWORD_DEFAULT);

            include __DIR__ . '/../models/registerModel.php';
            insertUser($first_Name, $last_Name, $email_, $hashed_password, $phone_Number);
        }
    }
}
