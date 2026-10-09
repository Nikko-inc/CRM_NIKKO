<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini-CRM</title>
</head>
<body>
    <?php require __DIR__ . '/partials/header.php'; ?>

    <main>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tableau de bord</h1>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-500">Nombre de clients</p>
                <p class="text-3xl font-bold text-gray-800"><?= $totalClients ?></p>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-sm text-gray-500">Chiffre d'affaires potentiel</p>
                <p class="text-3xl font-bold text-green-600"><?= number_format($caPotentiel, 2) ?> €</p>
            </div>
        </div>


        <h2 class="font-semibold text-gray-700 mb-3">Répartition par étape</h2>
        <div class="bg-white rounded-lg shadow-sm p-6 space-y-3">
            <?php
            $maxTotal = max(array_column($repartition, 'total')) ?: 1;
            ?>
            <?php foreach ($repartition as $ligne): ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span><?= e($ligne['nom']) ?></span>
                        <span><?= $ligne['total'] ?></span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full"
                            style="width: <?= ($ligne['total'] / $maxTotal) * 100 ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mt-4">
            <p class="text-sm text-gray-500">Taux de conversion</p>
            <p class="text-3xl font-bold text-blue-600"><?= $tauxConversion ?>%</p>
        </div>
    </main>

    <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>