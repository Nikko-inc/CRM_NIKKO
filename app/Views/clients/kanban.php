<?php
/** @var array $etapes */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= genererJetonCsrf() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>CRM-Nikko.Inc</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/kanban.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>
        <div x-data="kanban()">
            <h1>Pipeline commercial</h1>

            <div class="kanban">
                <?php foreach ($etapes as $etape): ?>
                    <div class="colonne"
                        @dragover.prevent
                        @drop="deposer(<?= (int) $etape['id'] ?>)">

                        <h2 class="colonne-titre"><?= e($etape['nom']) ?></h2>

                        <div class="colonne-cartes">
                            <?php foreach (($clientsParEtape[$etape['id']] ?? []) as $client): ?>
                                <div class="carte-client"
                                    draggable="true"
                                    @dragstart="clientDeplaceId = <?= (int) $client['id'] ?>">
                                    <h3><?= e($client['nom']) ?></h3>
                                    <p class="carte-email"><?= e($client['email']) ?></p>
                                    <a href="/clients/fiche?id=<?= (int) $client['id'] ?>"
                                        draggable="false"
                                        class="carte-lien">Voir la fiche →</a>
                                        <select class="carte-etape"
                                                aria-label="Changer l'étape de <?= e($client['nom']) ?>"
                                                @change="deplacer(<?= (int) $client['id'] ?>, $event.target.value)">
                                            <?php foreach ($etapes as $e): ?>
                                                <option value="<?= (int) $e['id'] ?>"
                                                    <?= (int) $e['id'] === (int) $etape['id'] ? 'selected' : '' ?>>
                                                    <?= e($e['nom']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- formulaire d'ajout client -->
            <div x-data="{ modalOuvert: <?= !empty($_SESSION['erreur']) ? 'true' : 'false' ?> }" class="zone-ajout">
                <button @click="modalOuvert = true" class="btn-principal">
                    + Nouveau client
                </button>

                <div x-show="modalOuvert" class="modal-fond" style="display: none;">
                    <div class="modal">
                        <h2>Nouveau client</h2>

                        <?php if (!empty($_SESSION['erreur'])): ?>
                            <p class="erreur"><?= e($_SESSION['erreur']) ?></p>
                            <?php unset($_SESSION['erreur']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/clients">
                            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">

                            <input type="text" name="nom" placeholder="Nom" required>
                            <input type="email" name="email" placeholder="Email">
                            <input type="tel" name="telephone" placeholder="Téléphone">
                            <input type="number" step="0.01" name="montant_potentiel" placeholder="Montant potentiel (€)">

                            <div class="modal-actions">
                                <button type="button" @click="modalOuvert = false" class="btn-annuler">
                                    Annuler
                                </button>
                                <button type="submit" class="btn-principal">
                                    Créer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../partials/footer.php'; ?>

    <script>
        function kanban() {
            return {
                clientDeplaceId: null,

                // Glisser-déposer (ordinateur)
                deposer(nouvelleEtapeId) {
                    this.deplacer(this.clientDeplaceId, nouvelleEtapeId);
                },

                // Changement d'étape par la liste déroulante (fonctionne au doigt)
                deplacer(clientId, nouvelleEtapeId) {
                    if (!clientId) return;

                    const jeton = document.querySelector('meta[name="csrf-token"]').content;
                    const donnees = new URLSearchParams({
                        id: clientId,
                        etape_id: nouvelleEtapeId,
                        csrf_token: jeton
                    });

                    fetch('/clients/etape', { method: 'POST', body: donnees })
                        .then(reponse => {
                            if (!reponse.ok) {
                                alert('Déplacement refusé (code ' + reponse.status + ')');
                                return;
                            }
                            window.location.reload();
                        });
                }
            };
        }
    </script>
</body>
</html>