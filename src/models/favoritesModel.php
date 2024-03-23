<?php

require_once 'models/connect.php';

// function add logements to favorites
if (!function_exists('addLogementsToFavorites')) {
    function addLogementsToFavorites($nameLogements_, $userId_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id FROM favorites WHERE id_logements=:logementId AND id_user=:userId");
            $stmt2->bindParam(':logementId', $logementId_);
            $stmt2->bindParam(':userId', $userId_);
            $stmt2->execute();
            $checkLogementsExistInFavorites = $stmt2->fetchColumn();

            if ($checkLogementsExistInFavorites) {
                echo "Le logement est déjà dans les favoris.";
            } else {
                $stmt3 = $dsn->prepare("INSERT INTO favorites (id_logements, id_user) VALUES (:logementId, :userId) ");
                $stmt3->bindParam(':logementId', $logementId_);
                $stmt3->bindParam(':userId', $userId_);
                $stmt3->execute();

                echo "le logements a bien été ajouter au favoris";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete logements to favorites
if (!function_exists('deleteLogementsToFavorites')) {
    function deleteLogementsToFavorites($nameLogements_, $userId_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id FROM favorites WHERE id_logements=:logementId AND id_user=:userId");
            $stmt2->bindParam(':logementId', $logementId_);
            $stmt2->bindParam(':userId', $userId_);
            $stmt2->execute();
            $checkLogementsExistInFavorites = $stmt2->fetchColumn();

            if ($checkLogementsExistInFavorites) {
                $stmt3 = $dsn->prepare("DELETE FROM favorites WHERE id_logements=:logementId AND id_user=:userId");
                $stmt3->bindParam(':logementId', $logementId_);
                $stmt3->bindParam(':userId', $userId_);
                $stmt3->execute();

                echo "le logements a bien été supprimer des favoris";
            } else {
                echo "logements inexistant";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}
