<!-- app/Views/partials/header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mini-CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow-sm px-6 py-4 mb-6">
        <div class="max-w-6xl mx-auto flex gap-6">
            <a href="/clients" class="text-gray-700 hover:text-blue-600 font-medium">Clients</a>
            <a href="/clients/kanban" class="text-gray-700 hover:text-blue-600 font-medium">Pipeline</a>
            <a href="/dashboard" class="text-gray-700 hover:text-blue-600 font-medium">Tableau de bord</a>

            <form method="POST" action="/logout" class="ml-auto">
                <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
                <button type="submit" class="text-gray-500 hover:text-red-600 font-medium">
                    Déconnexion
                </button>
            </form>
        </div>
    </nav>
</body>
</html>