<?php
// Utilitaire echapée les elements a afficher
function e(string $valeur): string
{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}


function genererJetonCsrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifierJetonCsrf(?string $jeton): bool
{
    return !empty($jeton) && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $jeton);
}

function exigerCsrf(): void
{
    if (!verifierJetonCsrf($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Requête invalide.');
    }
}



function estConnecte(): bool
{
    return !empty($_SESSION['utilisateur_id']);
}
?>