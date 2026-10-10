<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — CRM-Nikko.Inc</title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>

    <form method="POST" action="/login">
        <h1>Connexion</h1>

        <?php if (!empty($_SESSION['erreur'])): ?>
            <p class="erreur"><?= e($_SESSION['erreur']) ?></p>
            <?php unset($_SESSION['erreur']); ?>
        <?php endif; ?>

        <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus
               autocomplete="username">

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required
               autocomplete="current-password">

        <button type="submit">Se connecter</button>
    </form>

</body>
</html>