<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM-Nikko.Inc</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/fiche.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>
        <h1><?= e($client['nom']) ?></h1>
        <p class="fiche-email"><?= e($client['email']) ?></p>

        <form class="form-supprimer-client" method="POST" action="/clients/supprimer"
            onsubmit="return confirm('Supprimer ce client et toutes ses notes ?')">
            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
            <input type="hidden" name="id" value="<?= (int) $client['id'] ?>">
            <button type="submit" class="btn-supprimer">Supprimer ce client</button>
        </form>

        <a href="/clients/kanban" class="lien-retour">← Retour au pipeline</a>

        <form class="form-note" method="POST" action="/interactions">
            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
            <input type="hidden" name="client_id" value="<?= (int) $client['id'] ?>">
            <textarea name="note" placeholder="Nouvelle note..."></textarea>
            <button type="submit" class="btn-principal">Ajouter la note</button>
        </form>

        <div class="liste-notes">
            <?php foreach ($interactions as $interaction): ?>
                <div class="note">
                    <p class="note-texte"><?= nl2br(e($interaction['note'])) ?></p>
                    <p class="note-date"><?= e($interaction['date_interaction']) ?></p>

                    <form class="form-supprimer-note" method="POST" action="/interactions/supprimer"
                        onsubmit="return confirm('Supprimer cette note ?')">
                        <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
                        <input type="hidden" name="id" value="<?= (int) $interaction['id'] ?>">
                        <input type="hidden" name="client_id" value="<?= (int) $client['id'] ?>">
                        <button type="submit" class="btn-supprimer">Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>