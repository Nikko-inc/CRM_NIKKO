<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Mini-CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <form method="POST" action="/login" class="bg-white rounded-lg shadow-sm p-8 w-96">
        <h1 class="text-xl font-bold text-gray-800 mb-6">Connexion</h1>

        <?php if (!empty($_SESSION['erreur'])): ?>
            <p class="text-red-600 text-sm mb-4"><?= e($_SESSION['erreur']) ?></p>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">

        <label class="block text-sm text-gray-600 mb-1" for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus
               autocomplete="username"
               class="w-full border rounded p-2 mb-4">

        <label class="block text-sm text-gray-600 mb-1" for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required
               autocomplete="current-password"
               class="w-full border rounded p-2 mb-6">

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
            Se connecter
        </button>
    </form>

</body>
</html>