<?php
class DashboardController
{
    /*la propriété est une instance de la classe Client dans '../Models/Client.php'
    ce qui permet au controller de communiquer et d'user des methodes du modele
    */
    private Client $clientModel;

    public function __construct()
    {
        $this->clientModel = new Client();
    }

    public function afficher(): void
    {
        $totalClients = $this->clientModel->compter();
        $caPotentiel = $this->clientModel->chiffreAffairesPotentiel();
        $clientsParEtape = $this->clientModel->tousGroupesParEtape(); // pour le Kanban, voir plus bas
        $repartition = $this->clientModel->repartitionParEtape();
        $tauxConversion = $this->clientModel->tauxConversion();
        
        require __DIR__ . '/../Views/dashboard.php';
    }

}


?>