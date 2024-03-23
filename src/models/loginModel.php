<?php

require_once 'models/connect.php';

if (!function_exists('loginUser')) {
    function loginUser($login_email, $login_password)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT * FROM user WHERE email=:email");
            $stmt->bindParam(":email", $login_email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($login_password, $user['passwordUser'])) {
                $_SESSION['success'] = "You are logged in";
                $_SESSION['id'] = $user['id'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['lastName'] = $user['lastName'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['password'] = $user['passwordUser'];
                $_SESSION['phoneNumber'] = $user['phoneNumber'];
                $_SESSION['isAdmin'] = $user['isAdmin'];

                if ($user['isAdmin'] == 1) {
                    echo '<script>window.location.replace("/dashboard");</script>';
                    exit();
                } else {
                    echo '<script>window.location.replace("/");</script>';
                    exit();
                }
            } else {
                echo "Incorrect password";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
            echo $error;
        }
    }
}