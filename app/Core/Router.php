<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $path, array $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function resolve(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Captura onde o index.php está sendo executado em relação à raiz do servidor
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $scriptName = str_replace('\\', '/', $scriptName);
        $url = str_replace('\\', '/', $url);

        // Remove o sufixo /public da detecção de subpastas locais do Apache
        if (substr($scriptName, -7) === '/public') {
            $scriptName = substr($scriptName, 0, -7);
        }

        // Limpa o prefixo da pasta local (ex: /new-freela) da URL analisada
        if (!empty($scriptName) && $scriptName !== '/') {
            if (strpos($url, $scriptName) === 0) {
                $url = substr($url, strlen($scriptName));
            }
        }

        // Formatação final estrita da rota
        $cleanUrl = '/' . rtrim(ltrim($url, '/'), '/');

        if ($cleanUrl === '//' || $cleanUrl === '') {
            $cleanUrl = '/';
        }

        // Verifica se a rota solicitada existe no mapa de caminhos
        if (isset($this->routes[$method][$cleanUrl])) {
            $handler = $this->routes[$method][$cleanUrl];
            $controllerClass = $handler[0];
            $action = $handler[1];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        // Erro 404 customizado se a rota não bater
        http_response_code(404);
        echo "<h1 style='font-family:sans-serif; text-align:center; margin-top:50px; color:#dc3545;'>🚫 Rota não encontrada (Erro 404)</h1>";
        echo "<p style='font-family:sans-serif; text-align:center; color:#6c757d;'>O caminho <strong>[$method] $cleanUrl</strong> não existe neste ecossistema.</p>";
    }
}
