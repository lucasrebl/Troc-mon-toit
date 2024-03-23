<?php
require_once __DIR__ . '/controllers/registerController.php';
require_once __DIR__ . '/controllers/homeController.php';
require_once __DIR__ . '/controllers/loginController.php';
require_once __DIR__ . '/controllers/dashboardController.php';
require_once __DIR__ . '/models/createDatabase.php';
require_once __DIR__ . '/controllers/logementsController.php';
require_once __DIR__ . '/controllers/profilController.php';
require_once __DIR__ . '/controllers/favoritesController.php';
require_once __DIR__ . '/controllers/reservationController.php';

$routes =[
  '/' => ['controller' => 'homeController', 'method' => 'home'],
  '/register' => ['controller' => 'registerController', 'method' => 'dataRegister'],
  '/login' => ['controller' => 'loginController', 'method' => 'login'],
  '/dashboard' => ['controller' => 'dashboardController', 'method' => 'dashboard'],
  '/logements' => ['controller' => 'logementsController', 'method' => 'logements'],
  '/profil' => ['controller' => 'profilController', 'method' => 'profil'],
  '/favorites' => ['controller' => 'favoritesController', 'method' => 'favorites'],
  '/reservation' => ['controller' => 'reservationController', 'method' => 'reservation'],
];

$requestParts = explode('?', $_SERVER['REQUEST_URI'], 2);
$path = $requestParts[0];

if (array_key_exists($path, $routes)) {
  $controllerName = $routes[$path]['controller'];
  $methodName = $routes[$path]['method'];

  $controller = new $controllerName();
  $params = isset($requestParts[1]) ? $requestParts[1] : '';
  $controller->$methodName();

} else {
  http_response_code(404);
  require __DIR__ . '/Views/404.twig';
}