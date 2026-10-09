<?php
class ClientController
{
    /*la propriété est une instance de la classe Client dans '../Models/Client.php'
    ce qui permet au controller de communiquer et d'user des methodes du modele
    */
    private Client $clientModel;

    public function __construct()
    {
        $this->clientModel = new Client();
    }

    public function liste(): void
    {
        $clients = $this->clientModel->tous();
        require __DIR__ . '/../Views/clients/liste.php';
    }

    public function creer(): void
    {
        exigerCsrf();

        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nom === '') {
            $_SESSION['erreur'] = 'Le nom est obligatoire.';
            header('Location: /clients/kanban');
            exit;
        }

        $this->clientModel->creer([
            'etape_id' => 1, // "Prospect" par défaut
            'nom' => $nom,
            'email' => $email,
            'telephone' => trim($_POST['telephone'] ?? ''),
            'montant_potentiel' => (float) ($_POST['montant_potentiel'] ?? 0),
        ]);

        header('Location: /clients/kanban');
        exit;
    }

    // app/Controllers/ClientController.php

    public function supprimer(): void
    {
        exigerCsrf();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->clientModel->supprimer($id);
        }

        header('Location: /clients/kanban');
        exit;
    }

    // On recupere le `Client` (pour les infos) et ses `Interaction` (pour les notes)
    public function fiche(): void
    {
        $id = (int) $_GET['id'];
        $client = $this->clientModel->trouver($id);
        // on crée une INSTANCE du modèle Interaction, directement ici, dans cette méthode — contrairement à $this->clientModel qui, lui, est créé une fois dans le constructeur
        $interactionModel = new Interaction();
        $interactions = $interactionModel->pourClient($id);

        require __DIR__ . '/../Views/clients/fiche.php';
    }

    // On recupere les etapes pour le kanban
    public function Etape(): void
    {
        $etapeModel = new Etape();
        $etapes = $etapeModel->tous();

        require __DIR__ . '/../Views/clients/kanban.php';
    }

    // Recherche et filtre
    public function Rliste(): void
    {
        $terme = trim($_GET['q'] ?? '');
        $etapeId = (int) ($_GET['etape_id'] ?? 0);

        $clients = $this->clientModel->rechercher($terme, $etapeId);
        $etapes = (new Etape())->tous(); // pour remplir le menu déroulant de filtre

        require __DIR__ . '/../Views/clients/liste.php';
    }


    public function kanban(): void
    {
        $etapes = (new Etape())->tous();
        $clientsParEtape = $this->clientModel->tousGroupesParEtape();
        require __DIR__ . '/../Views/clients/kanban.php';
    }

    // Methode pour le drag and drop du kanban
    public function changerEtape(): void
    {
        exigerCsrf();

        $id = (int) ($_POST['id'] ?? 0);
        $etapeId = (int) ($_POST['etape_id'] ?? 0);

        if ($id > 0 && $etapeId > 0) {
            $this->clientModel->changerEtape($id, $etapeId);
        }
        // pas de redirection : la requête vient de fetch(), pas d'une vraie navigation
    }

   
}

?>