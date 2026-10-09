<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-CRM</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>
        <h1 class="text-2xl font-bold mb-4"><?= e($client['nom']) ?></h1>
        <p class="text-gray-500 mb-6"><?= e($client['email']) ?></p>

        <form method="POST" action="/clients/supprimer"
            onsubmit="return confirm('Supprimer ce client et toutes ses notes ?')">
            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
            <input type="hidden" name="id" value="<?= (int) $client['id'] ?>">
            <button type="submit" class="text-red-600 text-sm">Supprimer ce client</button>
        </form>
        <a href="/clients/kanban" class="text-sm text-blue-600 hover:underline">← Retour au pipeline</a>

        <form method="POST" action="/interactions" class="mb-6">
            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">

            <input type="hidden" name="client_id" value="<?= $client['id'] ?>">
            <textarea name="note" class="w-full border rounded p-2" placeholder="Nouvelle note..."></textarea>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Ajouter la note</button>
        </form>

        <div class="space-y-3">
            <?php foreach ($interactions as $interaction): ?>
                <div class="bg-white rounded p-3 shadow-sm">
                    <p><?= nl2br(e($interaction['note'])) ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= e($interaction['date_interaction']) ?></p>

                    <form method="POST" action="/interactions/supprimer"
                        onsubmit="return confirm('Supprimer cette note ?')">
                        <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
                        <input type="hidden" name="id" value="<?= (int) $interaction['id'] ?>">
                        <input type="hidden" name="client_id" value="<?= (int) $client['id'] ?>">
                        <button type="submit" class="text-xs text-red-600 mt-1">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>