<?php
/*if ($_ENV['APP_ENV'] === 'production') {
    ini_set('display_errors', '0');
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}*/

session_set_cookie_params([
    'httponly' => true,   // le cookie de session est inaccessible en JavaScript
    'samesite' => 'Lax',  // limite l'envoi automatique du cookie vers des sites tiers
    'secure' => false,    // true en production, une fois le HTTPS activé (Module 17)
]);

session_start();

require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/helpers.php';
require __DIR__ . '/../core/Database.php';


require __DIR__ . '/../app/Models/Client.php';
require __DIR__ . '/../app/Models/Etape.php';
require __DIR__ . '/../app/Models/Interaction.php';
require __DIR__ . '/../app/Models/Utilisateur.php';

require __DIR__ . '/../app/Controllers/DashboardController.php';
require __DIR__ . '/../app/Controllers/InteractionController.php';
require __DIR__ . '/../app/Controllers/ClientController.php';
require __DIR__ . '/../app/Controllers/AuthController.php';

$router = new Router();

$router->ajouter('GET', '/clients/kanban', [ClientController::class, 'kanban']);
$router->ajouter('GET', '/dashboard', [DashboardController::class, 'afficher']);
$router->ajouter('GET', '/clients', [ClientController::class, 'Rliste']);
$router->ajouter('GET', '/clients/fiche', [ClientController::class, 'fiche']);

$router->ajouter('POST', '/clients', [ClientController::class, 'creer']);
$router->ajouter('POST', '/clients/etape', [ClientController::class, 'changerEtape']);
$router->ajouter('POST', '/clients/supprimer', [ClientController::class, 'supprimer']);
$router->ajouter('POST', '/interactions', [InteractionController::class, 'ajouter']);
$router->ajouter('POST', '/interactions/supprimer', [InteractionController::class, 'supprimer']);

$router->ajouter('GET',  '/',       [ClientController::class, 'kanban']);
$router->ajouter('GET', '/login', [AuthController::class, 'formulaire']);
$router->ajouter('POST', '/login', [AuthController::class, 'connecter']);
$router->ajouter('POST', '/logout', [AuthController::class, 'deconnecter']);


$cheminActuel = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// La seule page accessibles sans être connecté
$routesPubliques = ['/login'];

if (!estConnecte() && !in_array($cheminActuel, $routesPubliques, true)) {
    header('Location: /login');
    exit;
}

$router->dispatcher($_SERVER['REQUEST_METHOD'], $cheminActuel);

?>