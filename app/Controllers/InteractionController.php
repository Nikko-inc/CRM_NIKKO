<?php
class InteractionController
{
    /*la propriété est une instance de la classe Client dans '../Models/Interaction.php'
    ce qui permet au controller de communiquer et d'user des methodes du modele
    */
    private Interaction $interactionModel;

    public function __construct()
    {
        $this->interactionModel = new Interaction();
    }

    public function ajouter(): void
    {
        exigerCsrf();

        $clientId = (int) $_POST['client_id'];
        $note = trim($_POST['note'] ?? '');

        if ($note !== ''){
            $this->interactionModel->creer($clientId, $note);
        }

        header('Location: /clients/fiche?id=' . $clientId);
        exit();
    }

    public function supprimer(): void 
    {
        exigerCsrf();

        $id = (int) $_POST['client_id'];
        $clientId = (int) $_POST['client_id'];
        $this->interactionModel->supprimer($id);

        header('Location: /clients/fiche?id=' . $clientId);
        exit();
    }
}

?>