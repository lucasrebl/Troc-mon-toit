<?php

require_once 'models/connect.php';

// function reserve logements
if (!function_exists('reservLogements')) {
    function reservLogements($name_, $startDate_, $endDate_, $userId_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT price FROM logements WHERE name=:name");
            $stmt->bindParam(':name', $name_);
            $stmt->execute();
            $pricePerDay = $stmt->fetchColumn();

            $startDate = new DateTime($startDate_);
            $endDate = new DateTime($endDate_);
            $now = new DateTime();
            $duration = $startDate->diff($endDate)->days;

            $totalCost = $pricePerDay * $duration;

            $stmt2 = $dsn->prepare("SELECT COUNT(*) FROM booking 
            WHERE id_logements = (SELECT id FROM logements WHERE name=:name) 
            AND ((startDate <= :endDate AND endDate >= :startDate) 
            OR (startDate <= :startDate AND endDate >= :endDate))");
            $stmt2->bindParam(':name', $name_);
            $stmt2->bindParam(':startDate', $startDate_);
            $stmt2->bindParam(':endDate', $endDate_);
            $stmt2->execute();
            $count_ = $stmt2->fetchColumn();

            if ($endDate < $startDate) {
                echo "la date fin ne peut pas etre anterieur a la date de début";
            } else {

                if ($count_ > 0) {
                    echo "Le logement est déjà réservé pour cette période.";
                } else if ($startDate < $now || $endDate < $now) {
                    echo "Les dates de réservation ne peuvent pas être dans le passé.";
                } else {
                    $stmt3 = $dsn->prepare("SELECT id FROM logements WHERE name=:name");
                    $stmt3->bindParam(':name', $name_);
                    $stmt3->execute();
                    $logementId_ = $stmt3->fetchColumn();

                    echo "Le coût total de la réservation est : $totalCost €";

                    $stmt4 = $dsn->prepare("INSERT INTO booking (startDate, endDate, id_logements, id_user) VALUES (:startDate, :endDate, :logementId, :userId)");
                    $stmt4->bindParam(':startDate', $startDate_);
                    $stmt4->bindParam(':endDate', $endDate_);
                    $stmt4->bindParam(':logementId', $logementId_);
                    $stmt4->bindParam(':userId', $userId_);
                    $stmt4->execute();
                }
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}
