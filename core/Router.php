<?php
class Router
{
    private array $routes = [];

    public function ajouter(string $methode, string $chemin, array $action): void
    {
        $this->routes[]=[
            'methode' => $methode,
            'chemin' => $chemin,
            'action' => $action,
        ];
    }

    public function dispatcher(string $methodeRequete, string $uriRequete): void
    {
        foreach ($this->routes as $route) {
            if ($route['methode'] === $methodeRequete && $route['chemin'] === $uriRequete) {
                [$controleur, $methode] = $route['action'];
                $instance = new $controleur();
                $instance->$methode();
                return;
            }
        }

        http_response_code(404);
        echo "Page non trouvée";
    }
}

?>