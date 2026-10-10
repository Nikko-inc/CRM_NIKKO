<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM-Nikko.Inc</title>
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>

    <main>
        <h1>Tableau de bord</h1>

        <div class="stats-grid">
            <div class="carte">
                <p class="carte-label">Nombre de clients</p>
                <p class="carte-valeur"><?= $totalClients ?></p>
            </div>
            <div class="carte">
                <p class="carte-label">Chiffre d'affaires potentiel</p>
                <p class="carte-valeur carte-valeur--vert"><?= number_format($caPotentiel, 2) ?> €</p>
            </div>
        </div>

        <h2>Répartition par étape</h2>
        <div class="carte repartition">
            <?php
            $maxTotal = max(array_column($repartition, 'total')) ?: 1;
            ?>
            <?php foreach ($repartition as $ligne): ?>
                <div>
                    <div class="repartition-entete">
                        <span><?= e($ligne['nom']) ?></span>
                        <span><?= $ligne['total'] ?></span>
                    </div>
                    <div class="barre">
                        <div class="barre-remplissage"
                            style="width: <?= ($ligne['total'] / $maxTotal) * 100 ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="carte carte-conversion">
            <p class="carte-label">Taux de conversion</p>
            <p class="carte-valeur carte-valeur--bleu"><?= $tauxConversion ?>%</p>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>