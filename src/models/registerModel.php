<?php

require_once 'models/connect.php';

if (!function_exists('insertUser')) {
    function insertUser($first_Name, $last_Name, $email_, $hashed_password, $phone_Number)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT COUNT(*) FROM user WHERE email = :email OR phoneNumber = :phoneNumber");
            $stmt->bindParam(':email', $email_);
            $stmt->bindParam(':phoneNumber', $phone_Number);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {
                echo "email ou numero de telephone deja existant";
                return;
            } else {
                $stmt2 = $dsn->prepare("INSERT INTO user (firstName, lastName, email, passwordUser, phoneNumber) VALUES (:firstName, :lastName, :email, :passwordUser, :phoneNumber) ");
                $stmt2->bindParam(':lastName', $last_Name);
                $stmt2->bindParam(':firstName', $first_Name);
                $stmt2->bindParam(':email', $email_);
                $stmt2->bindParam(':passwordUser', $hashed_password);
                $stmt2->bindParam(':phoneNumber', $phone_Number);
                $stmt2->execute();
                echo "register OK";
            }

        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}
