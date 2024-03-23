<?php

require_once 'models/connect.php';
require 'vendor/autoload.php';
use Faker\Factory;

$dsn = new PDO("mysql:host=mysql;dbname=my_database", "my_user", "my_password");
$dsn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $faker = Factory::create();

$createTable = ("CREATE TABLE IF NOT EXISTS
`user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastName` varchar(255) DEFAULT NULL,
  `firstName` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `passwordUser` varchar(255) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT unique_email_phoneNumber UNIQUE (`email`, `phoneNumber`)
) ENGINE = InnoDB AUTO_INCREMENT = 14 DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`logements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price` float DEFAULT NULL,
  `image` LONGBLOB DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT unique_nameLogements UNIQUE (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`services` (
  `id_services` int(11) NOT NULL AUTO_INCREMENT,
  `nameServices` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_services`),
  CONSTRAINT unique_nameServices UNIQUE (`nameServices`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`logementsType` (
  `id_logementsType` int(11) NOT NULL AUTO_INCREMENT,
  `nameLogementsType` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_logementsType`),
  CONSTRAINT unique_nameLogementsType UNIQUE (`nameLogementsType`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`equipements` (
  `id_equipements` int(11) NOT NULL AUTO_INCREMENT,
  `nameEquipements` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_equipements`),
  CONSTRAINT unique_nameEquipements UNIQUE (`nameEquipements`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`equipementsLogements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_logements` int(11) DEFAULT NULL,
  `id_equipements` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_equipementsLogements FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
  CONSTRAINT fk_equipementsLogements2 FOREIGN KEY (`id_equipements`) REFERENCES equipements(`id_equipements`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`logementsTypeLogements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_logements` int(11) DEFAULT NULL,
  `id_logementsType` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_logementsTypeLogements FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
  CONSTRAINT fk_logementsTypeLogements2 FOREIGN KEY (`id_logementsType`) REFERENCES logementsType(`id_logementsType`),
  CONSTRAINT uniqueID UNIQUE (`id_logements`, `id_logementsType`),
  CONSTRAINT uniqueIdLogementsType UNIQUE (`id_logements`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`servicesLogements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_logements` int(11) DEFAULT NULL,
  `id_services` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_servicesLogements FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
  CONSTRAINT fk_servicesLogements2 FOREIGN KEY (`id_services`) REFERENCES services(`id_services`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`booking` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `startDate` date DEFAULT NULL,
    `endDate` date DEFAULT NULL,
    `id_logements` int(11) DEFAULT NULL,
    `id_user` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT fk_booking FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
    CONSTRAINT fk_booking2 FOREIGN KEY (`id_user`) REFERENCES user(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);


$createTable = ("CREATE TABLE IF NOT EXISTS
`notesComments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentNotes` int(11) DEFAULT NULL,
  `contentComments` text DEFAULT NULL,
  `id_logements` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_notesComments FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
  CONSTRAINT fk_notesComments2 FOREIGN KEY (`id_user`) REFERENCES user(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$createTable = ("CREATE TABLE IF NOT EXISTS
`favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_logements` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fk_favorites FOREIGN KEY (`id_logements`) REFERENCES logements(`id`),
  CONSTRAINT fk_favorites2 FOREIGN KEY (`id_user`) REFERENCES user(`id`)
) ENGINE = InnoDB DEFAULT CHARSET = latin1");
$dsn->exec($createTable);

$password = 'adminPassword';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$createUser = ("INSERT IGNORE INTO user (firstName, lastName, email, passwordUser, phoneNumber, isAdmin) VALUES ('admin', 'admin', 'admin@gmail.com', '$hashedPassword', '0102030405', '1')");
$dsn->exec($createUser);