<?php

require_once 'models/connect.php';

// function add logements
if (!function_exists('insertLogements')) {
    function insertLogements($price_, $city_, $name_, $imageContent)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO logements (price, image, city, name) VALUES (:price, :image, :city, :name)");
            $stmt->bindParam(':price', $price_);
            $stmt->bindParam(':city', $city_);
            $stmt->bindParam(':name', $name_);
            $stmt->bindParam(':image', $imageContent, PDO::PARAM_LOB);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reu";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert services to logements
if (!function_exists('insertServicesToLogement')) {
    function insertServicesToLogement($logementsNameSelect_, $addServicesToLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check = $dsn->prepare("SELECT COUNT(*) FROM servicesLogements 
                                         WHERE id_logements = (SELECT id FROM logements WHERE name=:logementsNameSelect) 
                                         AND id_services = (SELECT id_services FROM services WHERE nameServices=:addServicesToLogements)");
            $check->bindParam(':logementsNameSelect', $logementsNameSelect_);
            $check->bindParam(':addServicesToLogements', $addServicesToLogements_);
            $check->execute();
            $count = $check->fetchColumn();

            if ($count > 0) {
                echo "Le service est déjà associé à ce logement.";
            } else {
                $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:logementsNameSelect");
                $stmt->bindParam(':logementsNameSelect', $logementsNameSelect_);
                $stmt->execute();
                $logementId = $stmt->fetchColumn();

                $stmt2 = $dsn->prepare("SELECT id_services FROM services WHERE nameServices=:addServicesToLogements");
                $stmt2->bindParam(':addServicesToLogements', $addServicesToLogements_);
                $stmt2->execute();
                $servicesId = $stmt2->fetchColumn();

                $stmt3 = $dsn->prepare("INSERT INTO servicesLogements (id_logements, id_services) VALUES (:logementId, :servicesId)");
                $stmt3->bindParam(':logementId', $logementId);
                $stmt3->bindParam(':servicesId', $servicesId);
                $stmt3->execute();

                echo "easy";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert equipements to logements
if (!function_exists('insertEquipementsToLogement')) {
    function insertEquipementsToLogement($logementsNameSelect_, $addEquipementsToLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check = $dsn->prepare("SELECT COUNT(*) FROM equipementsLogements 
                                         WHERE id_logements = (SELECT id FROM logements WHERE name=:logementsNameSelect) 
                                         AND id_equipements = (SELECT id_equipements FROM equipements WHERE nameEquipements=:addEquipementsToLogements)");
            $check->bindParam(':logementsNameSelect', $logementsNameSelect_);
            $check->bindParam(':addEquipementsToLogements', $addEquipementsToLogements_);
            $check->execute();
            $count = $check->fetchColumn();

            if ($count > 0) {
                echo "L'équipement est déjà associé à ce logement.";
            } else {
                $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:logementsNameSelect");
                $stmt->bindParam(':logementsNameSelect', $logementsNameSelect_);
                $stmt->execute();
                $logementId = $stmt->fetchColumn();

                $stmt2 = $dsn->prepare("SELECT id_equipements FROM equipements WHERE nameEquipements=:addEquipementsToLogements");
                $stmt2->bindParam(':addEquipementsToLogements', $addEquipementsToLogements_);
                $stmt2->execute();
                $equipementsId = $stmt2->fetchColumn();

                $stmt3 = $dsn->prepare("INSERT INTO equipementsLogements (id_logements, id_equipements) VALUES (:logementId, :equipementsId)");
                $stmt3->bindParam(':logementId', $logementId);
                $stmt3->bindParam(':equipementsId', $equipementsId);
                $stmt3->execute();

                echo "easy";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert logementsType to logements
if (!function_exists('insertLogementsTypeToLogement')) {
    function insertLogementsTypeToLogement($logementsNameSelect_, $addLogementTypeToLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:logementsNameSelect");
            $stmt->bindParam(':logementsNameSelect', $logementsNameSelect_);
            $stmt->execute();
            $logementId = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_logementsType FROM logementsType WHERE nameLogementsType=:addLogementTypeToLogements");
            $stmt2->bindParam(':addLogementTypeToLogements', $addLogementTypeToLogements_);
            $stmt2->execute();
            $logementsTypeId = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("INSERT INTO logementsTypeLogements (id_logements, id_logementsType) VALUES (:logementId, :logementsTypeId)");
            $stmt3->bindParam(':logementId', $logementId);
            $stmt3->bindParam(':logementsTypeId', $logementsTypeId);
            $stmt3->execute();

            echo "easy";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert equipements
if (!function_exists('inserEquipements')) {
    function inserEquipements($nameEquipements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO equipements (nameEquipements) VALUES (:nameEquipements)");
            $stmt->bindParam(':nameEquipements', $nameEquipements_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert services
if (!function_exists('insertServices')) {
    function insertServices($nameServices_)
    {
        try {

            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO services (nameServices) VALUES (:nameServices)");
            $stmt->bindParam(':nameServices', $nameServices_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function insert logements type
if (!function_exists('insertLogementsType')) {
    function insertLogementsType($nameLogementsType_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO logementsType (nameLogementsType) VALUES (:nameLogementsType)");
            $stmt->bindParam(':nameLogementsType', $nameLogementsType_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete logements
if (!function_exists('deleteLogements')) {
    function deleteLogements($nameDelete_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM logements WHERE name = :nameDelete");
            $stmt->bindParam(':nameDelete', $nameDelete_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis8";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete equipements
if (!function_exists('deleteEquipements')) {
    function  deleteEquipements($nameEquipementsDelete_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM equipements WHERE nameEquipements = :nameEquipementsDelete");
            $stmt->bindParam(':nameEquipementsDelete', $nameEquipementsDelete_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete services
if (!function_exists('deleteServices')) {
    function deleteServices($servicesDelete_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM services WHERE nameServices = :nameServicesDelete");
            $stmt->bindParam(':nameServicesDelete', $servicesDelete_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete logements type
if (!function_exists('deleteLogementsType')) {
    function deleteLogementsType($nameLogementsTypeDelete_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM logementsType WHERE nameLogementsType = :nameLogementsTypeDelete");
            $stmt->bindParam(':nameLogementsTypeDelete', $nameLogementsTypeDelete_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete user
if (!function_exists('deleteUser')) {
    function deleteUSer($userDelete_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM user WHERE email = :userDelete");
            $stmt->bindParam(':userDelete', $userDelete_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis9";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete equipements in logements
if (!function_exists('deleteEquipementsInLogements')) {
    function deleteEquipementsInLogements($nameLogements_, $nameEquipementsDeleteInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_equipements FROM equipements WHERE nameEquipements=:nameServicesDeleteInLogements");
            $stmt2->bindParam(':nameServicesDeleteInLogements', $nameEquipementsDeleteInLogements_);
            $stmt2->execute();
            $equipementsId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("DELETE FROM equipementsLogements WHERE id_logements=:logementId AND id_equipements=:equipementsId");
            $stmt3->bindParam(':logementId', $logementId_);
            $stmt3->bindParam(':equipementsId', $equipementsId_);
            $stmt3->execute();

            echo "azertzsfdsfvbfdgdhdr";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete services in logements
if (!function_exists('deleteServicesInLogements')) {
    function deleteServicesInLogements($nameLogements_, $nameServicesDeleteInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_services FROM services WHERE nameServices=:nameServicesDeleteInLogements");
            $stmt2->bindParam(':nameServicesDeleteInLogements', $nameServicesDeleteInLogements_);
            $stmt2->execute();
            $servicesId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("DELETE FROM servicesLogements WHERE id_logements=:logementId AND id_services=:servicesId");
            $stmt3->bindParam(':logementId', $logementId_);
            $stmt3->bindParam(':servicesId', $servicesId_);
            $stmt3->execute();

            echo "vvv";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function delete logements type in logements
if (!function_exists('deleteLogementsTypeInLogements')) {
    function deleteLogementsTypeInLogements($nameLogements_, $nameLogementsTypeDeleteInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_logementsType FROM logementsType WHERE nameLogementsType=:nameLogementsTypeDeleteInLogements");
            $stmt2->bindParam(':nameLogementsTypeDeleteInLogements', $nameLogementsTypeDeleteInLogements_);
            $stmt2->execute();
            $logemenstTypeId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("DELETE FROM logementsTypeLogements WHERE id_logements=:logementId AND id_logementsType=:logemenstTypeId");
            $stmt3->bindParam(':logementId', $logementId_);
            $stmt3->bindParam(':logemenstTypeId', $logemenstTypeId_);
            $stmt3->execute();

            echo "vvv898888";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements name
if (!function_exists('upadteLogementsName')) {
    function upadteLogementsName($nameSelect_, $nameUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE logements SET name=:nameUpdate WHERE name=:nameSelect");
            $stmt->bindParam(':nameSelect', $nameSelect_);
            $stmt->bindParam(':nameUpdate', $nameUpdate_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis978";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements price
if (!function_exists('upadteLogementsPrice')) {
    function upadteLogementsPrice($nameSelect_, $priceUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE logements SET price=:priceUpdate WHERE name=:nameSelect");
            $stmt->bindParam(':nameSelect', $nameSelect_);
            $stmt->bindParam(':priceUpdate', $priceUpdate_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis97588";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements city
if (!function_exists('upadteLogementsCity')) {
    function upadteLogementsCity($nameSelect_, $cityUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE logements SET city=:cityUpdate WHERE name=:nameSelect");
            $stmt->bindParam(':nameSelect', $nameSelect_);
            $stmt->bindParam(':cityUpdate', $cityUpdate_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis975vdsml88";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements image
if (!function_exists('updateLogementsImage')) {
    function updateLogementsImage($nameSelect_, $imageContent)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE logements set image=:imageUpdate WHERE name=:nameSelect");
            $stmt->bindParam(':nameSelect', $nameSelect_);
            $stmt->bindParam(':imageUpdate', $imageContent, PDO::PARAM_LOB);
            if ($stmt->execute()) {
                echo "poiuytre";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update services
if (!function_exists('updateEquipements')) {
    function updateEquipements($nameEquipementsSelect_, $nameEquipementsUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE equipements SET nameEquipements=:nameEquipementsUpdate WHERE nameEquipements=:nameEquipementsSelect");
            $stmt->bindParam(':nameEquipementsUpdate', $nameEquipementsUpdate_);
            $stmt->bindParam(':nameEquipementsSelect', $nameEquipementsSelect_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update services
if (!function_exists('updateServices')) {
    function updateServices($nameServicesSelect_, $nameServicesUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE services SET nameServices=:nameServicesUpdate WHERE nameServices=:nameServicesSelect");
            $stmt->bindParam(':nameServicesUpdate', $nameServicesUpdate_);
            $stmt->bindParam(':nameServicesSelect', $nameServicesSelect_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis2";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements type
if (!function_exists('updateLogementsType')) {
    function updateLogementsType($logementsTypeSelect_, $logementsTypeUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE logementsType SET nameLogementsType=:logementsTypeUpdate WHERE nameLogementsType=:logementsTypeSelect");
            $stmt->bindParam(':logementsTypeUpdate', $logementsTypeUpdate_);
            $stmt->bindParam(':logementsTypeSelect', $logementsTypeSelect_);
            $stmt->execute();
            if ($stmt->execute()) {
                echo "reussis3";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function upadte equipements in logements
if (!function_exists('updateEquipementsInLogements')) {
    function updateEquipementsInLogements($nameLogements_, $nameEquipementsSelect_, $nameEquipementsUpdateInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_equipements FROM equipements WHERE nameEquipements=:nameEquipementsSelect");
            $stmt2->bindParam(':nameEquipementsSelect', $nameEquipementsSelect_);
            $stmt2->execute();
            $equipementsSelectId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("SELECT id_equipements FROM equipements WHERE nameEquipements=:nameEquipementsUpdateInLogements");
            $stmt3->bindParam(':nameEquipementsUpdateInLogements', $nameEquipementsUpdateInLogements_);
            $stmt3->execute();
            $equipementsUpdateId_ = $stmt3->fetchColumn();

            $stmt4 = $dsn->prepare("UPDATE equipementsLogements SET id_equipements=:equipementsUpdateId WHERE id_equipements=:equipementsSelectId AND id_logements=:logementId");
            $stmt4->bindParam(':equipementsUpdateId', $equipementsUpdateId_);
            $stmt4->bindParam(':equipementsSelectId', $equipementsSelectId_);
            $stmt4->bindParam(':logementId', $logementId_);
            $stmt4->execute();

            echo "SDIHODSIUVJWDX";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update services in logements
if (!function_exists('updateServicesInLogements')) {
    function updateServicesInLogements($nameLogements_, $nameServicesSelect_, $nameServicesUpdateInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_services FROM services WHERE nameServices=:nameServicesSelect");
            $stmt2->bindParam(':nameServicesSelect', $nameServicesSelect_);
            $stmt2->execute();
            $servicesSelectId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("SELECT id_services FROM services WHERE nameServices=:nameServicesUpdateInLogements");
            $stmt3->bindParam(':nameServicesUpdateInLogements', $nameServicesUpdateInLogements_);
            $stmt3->execute();
            $servicesUpdateId_ = $stmt3->fetchColumn();

            $stmt4 = $dsn->prepare("UPDATE servicesLogements SET id_services=:servicesUpdateId WHERE id_services=:servicesSelectId AND id_logements=:logementId");
            $stmt4->bindParam(':servicesUpdateId', $servicesUpdateId_);
            $stmt4->bindParam(':servicesSelectId', $servicesSelectId_);
            $stmt4->bindParam(':logementId', $logementId_);
            $stmt4->execute();

            echo "SDIHODSIUV454565JWDX";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update logements type in logements
if (!function_exists('updateLogementsTypeInLogements')) {
    function updateLogementsTypeInLogements($nameLogements_, $logementsTypeSelect_, $logementsTypeUpdateInLogements_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("SELECT id FROM logements WHERE name=:nameLogements");
            $stmt->bindParam(':nameLogements', $nameLogements_);
            $stmt->execute();
            $logementId_ = $stmt->fetchColumn();

            $stmt2 = $dsn->prepare("SELECT id_logementsType FROM logementsType WHERE nameLogementsType=:logementsTypeSelect");
            $stmt2->bindParam(':logementsTypeSelect', $logementsTypeSelect_);
            $stmt2->execute();
            $logementsTypeSelectId_ = $stmt2->fetchColumn();

            $stmt3 = $dsn->prepare("SELECT id_logementsType FROM logementsType WHERE nameLogementsType=:logementsTypeUpdateInLogements");
            $stmt3->bindParam(':logementsTypeUpdateInLogements', $logementsTypeUpdateInLogements_);
            $stmt3->execute();
            $logementsTypeUpdateId_ = $stmt3->fetchColumn();

            $stmt4 = $dsn->prepare("UPDATE logementsTypeLogements SET id_logementsType=:logementsTypeUpdateId WHERE id_logementsType=:logementsTypeSelectId AND id_logements=:logementId");
            $stmt4->bindParam(':logementsTypeUpdateId', $logementsTypeUpdateId_);
            $stmt4->bindParam(':logementsTypeSelectId', $logementsTypeSelectId_);
            $stmt4->bindParam(':logementId', $logementId_);
            $stmt4->execute();

            echo "SDIHODSIUV454565JW8465d9e8qf7qz54fqzf98qzf6qz6DX";
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update user email
if (!function_exists('updateUserEmail')) {
    function updateUserEmail($userEmailSelect_, $userEmailUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE user SET email=:userEmailUpdate WHERE email=:userEmailSelect");
            $stmt->bindParam(':userEmailSelect', $userEmailSelect_);
            $stmt->bindParam(':userEmailUpdate', $userEmailUpdate_);
            if ($stmt->execute()) {
                echo "reussis15";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update user firstName
if (!function_exists('updateUserFirstName')) {
    function updateUserFirstName($userEmailSelect_, $userFirstNameUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE user SET firstName=:userFirstNameUpdate WHERE email=:userEmailSelect");
            $stmt->bindParam(':userFirstNameUpdate', $userFirstNameUpdate_);
            $stmt->bindParam(':userEmailSelect', $userEmailSelect_);
            if ($stmt->execute()) {
                echo "reussis16";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update user lastNAme
if (!function_exists('updateUserLastName')) {
    function updateUserLastName($userEmailSelect_, $userLastNameUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE user SET lastName=:userLastNameUpdate WHERE email=:userEmailSelect");
            $stmt->bindParam(':userLastNameUpdate', $userLastNameUpdate_);
            $stmt->bindParam(':userEmailSelect', $userEmailSelect_);
            if ($stmt->execute()) {
                echo "reussis17";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update user password
if (!function_exists('updateUserPassword')) {
    function updateUserPassword($userEmailSelect_, $hashed_password_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE user SET passwordUser=:userPasswordUSerUpdate WHERE email=:userEmailSelect");
            $stmt->bindParam(':userPasswordUSerUpdate', $hashed_password_);
            $stmt->bindParam(':userEmailSelect', $userEmailSelect_);
            if ($stmt->execute()) {
                echo "reussis18";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function update user phoneNumber
if (!function_exists('updateUserPhoneNumber')) {
    function updateUserPhoneNumber($userEmailSelect_, $userPhoneNumberUpdate_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE user SET phoneNumber=:userPhoneNumberUpdate WHERE email=:userEmailSelect");
            $stmt->bindParam(':userEmailSelect', $userEmailSelect_);
            $stmt->bindParam(':userPhoneNumberUpdate', $userPhoneNumberUpdate_);
            if ($stmt->execute()) {
                echo "reussis48";
            } else {
                echo "err";
            }
        } catch (PDOException $e) {
            $error  = "error: " . $e->getMessage();
        }
    }
}

// function add notes and comments to a logements
if (!function_exists('insertNotesComments')) {
    function insertNotesComments($contentNotes_, $contentComments_, $id_logements_, $id_user_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("INSERT INTO notesComments (contentNotes, contentComments, id_logements, id_user) VALUES (:contentNotes, :contentComments, :id_logements, :id_user)");
            $stmt->bindParam(':contentNotes', $contentNotes_);
            $stmt->bindParam(':contentComments', $contentComments_);
            $stmt->bindParam(':id_logements', $id_logements_);
            $stmt->bindParam(':id_user', $id_user_);
            $stmt->execute();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
            echo $error;
        }
    }
}

// dunction delete notes and commen to a logements
if (!function_exists('deleteNotesComments')) {
    function deleteNotesComments($id_logements_, $id_user_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("DELETE FROM notesComments WHERE id_logements=:id_logements AND id_user=:id_user");
            $stmt->bindParam(':id_logements', $id_logements_);
            $stmt->bindParam(':id_user', $id_user_);
            $stmt->execute();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
            echo $error;
        }
    }
}

// function update notes and commen to a logements
if (!function_exists('updateNotesComments')) {
    function updateNotesComments($contentNotes_, $contentComments_, $id_logements_, $id_user_)
    {
        try {
            $dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
            $dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $dsn->prepare("UPDATE notesComments SET contentNotes=:contentNotes, contentComments=:contentComments WHERE id_logements=:id_logements AND id_user=:id_user");
            $stmt->bindParam(':contentNotes', $contentNotes_);
            $stmt->bindParam(':contentComments', $contentComments_);
            $stmt->bindParam(':id_logements', $id_logements_);
            $stmt->bindParam(':id_user', $id_user_);
            $stmt->execute();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
            echo $error;
        }
    }
}