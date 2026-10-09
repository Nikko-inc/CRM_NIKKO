<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>


    <main>
        <h1>Mes clients</h1>

        <?php if (empty($clients)): ?>
            <p>Aucun client pour le moment.</p>
        <?php else: ?>
            <table>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= e($client['nom']) ?></td>
                        <td><?= e($client['email']) ?></td>
                        <td><?= number_format($client['montant_potentiel'], 2) ?> €</td>
                        <td><a href="/clients/fiche?id=<?= (int) $client['id'] ?>" class="text-blue-600 hover:underline">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
        
        <!-- Formulaire de Recherche et filtre -->
        <form method="GET" action="/clients" class="flex gap-3 mb-6">
            <input type="text" name="q" value="<?= e($_GET['q'] ?? '') ?>"
                placeholder="Rechercher un client..." class="border rounded p-2 flex-1">

            <select name="etape_id" class="border rounded p-2">
                <option value="0">Toutes les étapes</option>
                <?php foreach ($etapes as $etape): ?>
                    <option value="<?= $etape['id'] ?>"
                        <?= ((int)($_GET['etape_id'] ?? 0) === (int)$etape['id']) ? 'selected' : '' ?>>
                        <?= e($etape['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
        </form>
    </main>


    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>