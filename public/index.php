<?php
// Où se trouvent app/, core/ et config/ ?
//  - en local (WAMP)    : à côté du dossier public/  -> le dossier parent
//  - chez InfinityFree  : à côté de ce index.php     -> ce dossier
$racine = is_dir(__DIR__ . '/app') ? __DIR__ : dirname(__DIR__);

// Local ou production ?
$enLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', 'mini-crm.local'], true);


// En local on affiche les erreurs ; en ligne on les cache au visiteur
error_reporting(E_ALL);
ini_set('display_errors', $enLocal ? '1' : '0');

ini_set('log_errors', '1');
ini_set('error_log', $racine . '/logs/erreurs.log');

session_set_cookie_params([
    'httponly' => true,       // le cookie de session est inaccessible en JavaScript
    'samesite' => 'Lax',      // limite l'envoi automatique du cookie vers des sites tiers
    'secure'   => !$enLocal,  // true en ligne : nécessite que le HTTPS soit actif
]);

session_start();

require $racine . '/core/Router.php';
require $racine . '/core/helpers.php';
require $racine . '/core/Database.php';

require $racine . '/app/Models/Client.php';
require $racine . '/app/Models/Etape.php';
require $racine . '/app/Models/Interaction.php';
require $racine . '/app/Models/Utilisateur.php';

require $racine . '/app/Controllers/DashboardController.php';
require $racine . '/app/Controllers/InteractionController.php';
require $racine . '/app/Controllers/ClientController.php';
require $racine . '/app/Controllers/AuthController.php';

$router = new Router();

// --- Pages à afficher (GET) ---
$router->ajouter('GET', '/', [ClientController::class, 'kanban']);
$router->ajouter('GET', '/clients/kanban', [ClientController::class, 'kanban']);
$router->ajouter('GET', '/dashboard', [DashboardController::class, 'afficher']);
$router->ajouter('GET', '/clients', [ClientController::class, 'Rliste']);
$router->ajouter('GET', '/clients/fiche', [ClientController::class, 'fiche']);
$router->ajouter('GET', '/login', [AuthController::class, 'formulaire']);

// --- Actions (POST) ---
$router->ajouter('POST', '/clients', [ClientController::class, 'creer']);
$router->ajouter('POST', '/clients/etape', [ClientController::class, 'changerEtape']);
$router->ajouter('POST', '/clients/supprimer', [ClientController::class, 'supprimer']);
$router->ajouter('POST', '/interactions', [InteractionController::class, 'ajouter']);
$router->ajouter('POST', '/interactions/supprimer', [InteractionController::class, 'supprimer']);
$router->ajouter('POST', '/login', [AuthController::class, 'connecter']);
$router->ajouter('POST', '/logout', [AuthController::class, 'deconnecter']);

$cheminActuel = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// La seule page accessible sans être connecté
$routesPubliques = ['/login'];

if (!estConnecte() && !in_array($cheminActuel, $routesPubliques, true)) {
    header('Location: /login');
    exit;
}

$router->dispatcher($_SERVER['REQUEST_METHOD'], $cheminActuel);