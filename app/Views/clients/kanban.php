
<?php
/** @var array $etapes */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= genererJetonCsrf() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <title>Mini-CRM</title>
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <main>

        <div x-data="kanban()">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Pipeline commercial</h1>

            <div class="grid grid-cols-4 gap-4">
                <?php foreach ($etapes as $etape): ?>
                    <div class="bg-gray-50 rounded-lg p-3"
                        @dragover.prevent
                        @drop="deposer(<?= $etape['id'] ?>)">

                        <h2 class="font-semibold text-gray-700 mb-3"><?= e($etape['nom']) ?></h2>

                        <div class="space-y-3">
                            <?php foreach (($clientsParEtape[$etape['id']] ?? []) as $client): ?>
                                <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md cursor-move"
                                    draggable="true"
                                    @dragstart="clientDeplaceId = <?= $client['id'] ?>">
                                    <h3 class="font-bold text-gray-800"><?= e($client['nom']) ?></h3>
                                    <p class="text-sm text-gray-500"><?= e($client['email']) ?></p>
                                    <a href="/clients/fiche?id=<?= (int) $client['id'] ?>"
                                        draggable="false"
                                        class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                                            Voir la fiche →
                                        </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- formulaire d'ajout client -->

            <div x-data="{ modalOuvert: <?= !empty($_SESSION['erreur']) ? 'true' : 'false' ?> }" class="mb-6 mx-auto">
                <button @click="modalOuvert = true" class="bg-blue-600 text-white px-4 py-2 rounded">
                    + Nouveau client
                </button>

                <div x-show="modalOuvert" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" style="display: none;">
                    <div class="bg-white rounded-lg p-6 w-96">
                        <h2 class="font-bold text-lg mb-4">Nouveau client</h2>

                        <?php if (!empty($_SESSION['erreur'])): ?>
                            <p class="text-red-600 text-sm mb-3"><?= e($_SESSION['erreur']) ?></p>
                            <?php unset($_SESSION['erreur']); ?>
                        <?php endif; ?>

                        <form method="POST" action="/clients">
                            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">

                            <input type="text" name="nom" placeholder="Nom" required class="w-full border rounded p-2 mb-3">
                            <input type="email" name="email" placeholder="Email" class="w-full border rounded p-2 mb-3">
                            <input type="tel" name="telephone" placeholder="Téléphone" class="w-full border rounded p-2 mb-3">
                            <input type="number" step="0.01" name="montant_potentiel" placeholder="Montant potentiel (€)" class="w-full border rounded p-2 mb-3">

                            <div class="flex justify-end gap-2">
                                <button type="button" @click="modalOuvert = false" class="px-4 py-2 text-gray-500">
                                    Annuler
                                </button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
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

                deposer(nouvelleEtapeId) {
                    if (!this.clientDeplaceId) return;

                    const jeton = document.querySelector('meta[name="csrf-token"]').content;
                    const donnees = new URLSearchParams({
                        id: this.clientDeplaceId,
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