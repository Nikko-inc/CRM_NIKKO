<?php
class Interaction 
{
    # une propriété qui stocke la connexion 
    private PDO $pdo;

    /* Constructeur s'exécute automatiquement à chaque fois qu'on fait `new Interaction()`. Ici, il va chercher la connexion partagée via le Singleton */
    public function __construct()
    {
        $this->pdo = Database::getConnexion();
    }

    public function pourClient(int $clientId): array
    {
        $query = "SELECT * FROM interactions WHERE client_id = :client_id ORDER BY date_interaction DESC";
        $requete = $this->pdo->prepare($query);
        $requete->execute(['client_id' => $clientId]);
        return $requete->fetchAll();
    }

    public function creer(int $clientId, string $note): int
    {
        $query = "INSERT INTO interactions (client_id, note) VALUES (:client_id, :note)";
        $requete = $this->pdo->prepare($query);
        $requete->execute(['client_id' => $clientId, 'note' => $note]);
        return (int) $this->pdo->lastInsertId();
    }

    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM interactions WHERE id = :id");
        return $requete->execute(['id' => $id]);
    }
}
?>