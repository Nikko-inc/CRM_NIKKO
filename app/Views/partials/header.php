<nav>
    <div class="nav-container">
        <a href="/clients">Clients</a>
        <a href="/clients/kanban">Pipeline</a>
        <a href="/dashboard">Tableau de bord</a>

        <form method="POST" action="/logout">
            <input type="hidden" name="csrf_token" value="<?= genererJetonCsrf() ?>">
            <button type="submit">Déconnexion</button>
        </form>
    </div>
</nav>