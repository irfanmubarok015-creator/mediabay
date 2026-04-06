<?php

class Router {
    private array $routes = [];
    private string $basePath;

    public function __construct(string $basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function get(string $path, string $controller, string $action): void {
        $this->addRoute('GET', $path, $controller, $action);
    }

    public function post(string $path, string $controller, string $action): void {
        $this->addRoute('POST', $path, $controller, $action);
    }

    public function any(string $path, string $controller, string $action): void {
        $this->addRoute('ANY', $path, $controller, $action);
    }

    private function addRoute(string $method, string $path, string $controller, string $action): void {
        $this->routes[] = [
            'method'     => $method,
            'path'       => $path,
            'controller' => $controller,
            'action'     => $action,
            'pattern'    => $this->pathToPattern($path),
        ];
    }

    private function pathToPattern(string $path): string {
        $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(): void {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = rawurldecode($requestUri);

        // Strip base path dengan aman
        if ($this->basePath !== '' && strpos($requestUri, $this->basePath) === 0) {
            $requestUri = substr($requestUri, strlen($this->basePath));
        }

        // Normalize: pastikan diawali /, tidak ada trailing slash kecuali root
        $requestUri = '/' . ltrim($requestUri, '/');
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if (($route['method'] === $requestMethod || $route['method'] === 'ANY') &&
                preg_match($route['pattern'], $requestUri, $matches)) {

                $params = array_filter($matches, fn($k) => !is_int($k), ARRAY_FILTER_USE_KEY);
                $this->invoke($route['controller'], $route['action'], $params);
                return;
            }
        }

        http_response_code(404);
        require __DIR__ . '/../app/views/errors/404.php';
    }

    private function invoke(string $controllerName, string $action, array $params): void {
        // Coba file controller individual dulu
        $controllerFile = __DIR__ . "/../app/controllers/{$controllerName}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
        }

        // Jika class belum ada, load bundle Controllers.php
        if (!class_exists($controllerName)) {
            $bundleFile = __DIR__ . "/../app/controllers/Controllers.php";
            if (file_exists($bundleFile)) {
                require_once $bundleFile;
            }
        }

        if (!class_exists($controllerName)) {
            http_response_code(500);
            die("<b>Error:</b> Controller class not found: <code>{$controllerName}</code>");
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            die("<b>Error:</b> Action not found: <code>{$controllerName}::{$action}()</code>");
        }

        call_user_func_array([$controller, $action], $params);
    }
}
