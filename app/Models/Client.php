<?php

class Client
{
    # une propriété qui stocke la connexion 
    private PDO $pdo;

    /* Constructeur s'exécute automatiquement à chaque fois qu'on fait `new Client()`. Ici, il va chercher la connexion partagée via le Singleton */
    public function __construct()
    {
        $this->pdo = Database::getConnexion();
    }


    # Toutes les actions en rapports avec la ressource (entité) clients 
    // READ
    public function tous():array
    {
        $requete = $this->pdo->query("SELECT * FROM clients ORDER BY cree_le DESC");
        return $requete->fetchAll();
    }

    public function trouver(int $id): ?array
    {
        $requete = $this->pdo->prepare("SELECT * FROM clients WHERE id = :id");
        $requete->execute(['id' => $id]);
        $resultat = $requete->fetch();
        return $resultat ?:null;
    }

    // CREATE
    public function creer(array $donnees): int
    {
        $query = "INSERT INTO clients (etape_id, nom, email, telephone, montant_potentiel)
                    VALUES (:etape_id, :nom, :email, :telephone, :montant_potentiel)";

        $requete = $this->pdo->prepare($query);
        $requete->execute([
            'etape_id' => $donnees['etape_id'],
            'nom' => $donnees['nom'],
            'email' => $donnees['email'],
            'telephone' => $donnees['telephone'],
            'montant_potentiel' => $donnees['montant_potentiel'],
        ]);   
        
        return (int) $this->pdo->lastInsertId();
    }

    // UPDATE
    public function modifier(int $id, array $donnees): bool
    {
        $query ="UPDATE clients 
                SET etape_id = :etape_id, nom = :nom, email = :email,
                        telephone = :telephone, montant_potentiel = :montant_potentiel
                WHERE id = :id";
        $requete = $this->pdo->prepare($query);
        return $requete->execute([
            'id' => $id,
            'etape_id' => $donnees['etape_id'],
            'nom' => $donnees['nom'],
            'email' => $donnees['email'],
            'telephone' => $donnees['telephone'],
            'montant_potentiel' => $donnees['montant_potentiel'],
        ]);        
    }

    // CHANGER D'ETAPE
    public function changerEtape(int $id, int $nouvelleEtapeId): bool
    {
        $requete = $this->pdo->prepare("UPDATE clients SET etape_id = :etape_id WHERE id = :id");
        return $requete->execute(['etape_id' => $nouvelleEtapeId, 'id' => $id]);
    }

    // DELETE
    public function supprimer(int $id): bool
    {
        $requete = $this->pdo->prepare("DELETE FROM clients WHERE id = :id");
        return $requete->execute(['id' => $id]);
    }
     
    # Statistiques pour le tableau de bord
    public function compter(): int
    {
        $requete = $this->pdo->query("SELECT COUNT(*) AS total FROM clients");
        return (int) $requete->fetch()['total'];
    }

    public function chiffreAffairesPotentiel(): float
    {
        $requete = $this->pdo->query("SELECT SUM(montant_potentiel) AS total FROM clients");
        return (float) ($requete->fetch()['total'] ?? 0);
    }

    # Récupérer les clients groupés par étape, pour le Kanban
    public function tousGroupesParEtape(): array
    {
        $query = "SELECT clients.*, etapes.nom AS etape_nom
                FROM clients
                JOIN etapes ON clients.etape_id = etapes.id
                ORDER BY etapes.ordre";

        $requete = $this->pdo->query($query);
        $tousLesClients = $requete->fetchAll();

        $groupes = [];
        foreach ($tousLesClients as $client) {
            $groupes[$client['etape_id']][] = $client;
        }

        return $groupes;
    }


     // Recherche et filtre
    public function rechercher(string $terme = '', int $etapeId = 0): array
    {
        $conditions = [];
        $parametres = [];

        if ($terme !== '') {
            $conditions[] = "(nom LIKE :terme OR email LIKE :terme)";
            $parametres['terme'] = '%' . $terme . '%';
        }

        if ($etapeId > 0) {
            $conditions[] = "etape_id = :etape_id";
            $parametres['etape_id'] = $etapeId;
        }

        $sql = "SELECT * FROM clients";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        $sql .= " ORDER BY cree_le DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($parametres);
        return $stmt->fetchAll();
    }


    /* Statistiques avancées du tableau de bord 
        combien de clients à chaque étape, 
        et le taux de conversion global
    */
    
        // combien de clients à chaque étape, 
    public function repartitionParEtape(): array
    {
        $query = "SELECT etapes.nom, COUNT(clients.id) AS total
                FROM etapes
                LEFT JOIN clients ON clients.etape_id = etapes.id
                GROUP BY etapes.id, etapes.nom
                ORDER BY etapes.ordre";

        $requete = $this->pdo->query($query);
        return $requete->fetchAll();
    }   
    
    
    // combien de prospects deviennent clients signés
    public function tauxConversion(): float
    {
        $total = $this->compter();
        if ($total === 0) {
            return 0.0;
        }

        $query = "SELECT COUNT(*) AS total FROM clients c
                JOIN etapes e ON c.etape_id = e.id
                WHERE e.nom = 'Client signé'";
        $requete = $this->pdo->query($query);
        $signes = (int) $requete->fetch()['total'];

        return round(($signes / $total) * 100, 1);
    }
}


?>