<?php
class Etape 
{
    # une propriété qui stocke la connexion 
    private PDO $pdo;

    /* Constructeur s'exécute automatiquement à chaque fois qu'on fait `new Etape()`. Ici, il va chercher la connexion partagée via le Singleton */
    public function __construct()
    {
        $this->pdo = Database::getConnexion();
    }


    // lister les étapes dans l'ordre
    public function tous(): array
    {
        $requete = $this->pdo->query("SELECT * FROM etapes ORDER BY ordre");
        return $requete->fetchAll();
    }
}



?>