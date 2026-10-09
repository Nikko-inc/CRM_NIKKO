<?php

class AuthController
{
    /*la propriété est une instance de la classe Client dans '../Models/Utilisateur.php'
    ce qui permet au controller de communiquer et d'user des methodes du modele
    */
    private Utilisateur $utilisateurModel;

    public function __construct()
    {
        $this->utilisateurModel = new Utilisateur();
    }

    // Affiche le formulaire (GET /login)
    public function formulaire(): void
    {
        if (estConnecte()) {
            header('Location: /clients/kanban');
            exit;
        }
        require __DIR__ . '/../Views/auth/login.php';
    }

    // Traite le formulaire (POST /login)
    public function connecter(): void
    {
        exigerCsrf();

        $email = trim($_POST['email'] ?? '');
        $motDePasse = $_POST['mot_de_passe'] ?? '';

        $utilisateur = $this->utilisateurModel->trouverParEmail($email);

        // Un seul message pour tous les échecs : on ne dit jamais
        // si c'est l'email ou le mot de passe qui est faux.
        if ($utilisateur === null || !password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            $_SESSION['erreur'] = 'Identifiants incorrects.';
            header('Location: /login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['utilisateur_id'] = (int) $utilisateur['id'];

        header('Location: /clients/kanban');
        exit;
    }

    // Déconnexion (POST /logout)
    public function deconnecter(): void
    {
        exigerCsrf();

        $_SESSION = [];
        session_destroy();

        header('Location: /login');
        exit;
    }
}


?>