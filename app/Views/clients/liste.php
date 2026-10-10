<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM-Nikko.Inc</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/liste.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>
        <h1>Mes clients</h1>

        <!-- Formulaire de recherche et filtre -->
        <form class="form-recherche" method="GET" action="/clients">
            <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>"
                placeholder="Rechercher un client...">

            <select name="etape_id">
                <option value="0">Toutes les étapes</option>
                <?php foreach ($etapes as $etape): ?>
                    <option value="<?= (int) $etape['id'] ?>"
                        <?= ((int) ($_GET['etape_id'] ?? 0) === (int) $etape['id']) ? 'selected' : '' ?>>
                        <?= e($etape['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-principal">Filtrer</button>
        </form>

        <?php if (empty($clients)): ?>
            <p class="message-vide">Aucun client pour le moment.</p>
        <?php else: ?>
            <div class="table-conteneur">
                <table class="table-clients">
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?= e($client['nom']) ?></td>
                            <td><?= e($client['email']) ?></td>
                            <td><?= number_format($client['montant_potentiel'], 2) ?> €</td>
                            <td><a href="/clients/fiche?id=<?= (int) $client['id'] ?>" class="lien-voir">Voir</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>