<?php 
class Utilisateur
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnexion();
    }

    public function trouverParEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $resultat = $stmt->fetch();
        return $resultat ?: null;
    }
}

?>