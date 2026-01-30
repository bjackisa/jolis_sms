<?php
namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private string $prefix = '';
    private array $groupMiddlewares = [];

    public function get(string $path, $handler, array $middlewares = []): self
    {
        return $this->addRoute('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): self
    {
        return $this->addRoute('POST', $path, $handler, $middlewares);
    }

    public function put(string $path, $handler, array $middlewares = []): self
    {
        return $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    public function patch(string $path, $handler, array $middlewares = []): self
    {
        return $this->addRoute('PATCH', $path, $handler, $middlewares);
    }

    public function delete(string $path, $handler, array $middlewares = []): self
    {
        return $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    public function group(array $options, callable $callback): self
    {
        $previousPrefix = $this->prefix;
        $previousMiddlewares = $this->groupMiddlewares;

        if (isset($options['prefix'])) {
            $this->prefix .= '/' . trim($options['prefix'], '/');
        }

        if (isset($options['middleware'])) {
            $middlewares = is_array($options['middleware']) ? $options['middleware'] : [$options['middleware']];
            $this->groupMiddlewares = array_merge($this->groupMiddlewares, $middlewares);
        }

        $callback($this);

        $this->prefix = $previousPrefix;
        $this->groupMiddlewares = $previousMiddlewares;

        return $this;
    }

    private function addRoute(string $method, string $path, $handler, array $middlewares = []): self
    {
        $fullPath = $this->prefix . '/' . trim($path, '/');
        $fullPath = '/' . trim($fullPath, '/');
        
        $allMiddlewares = array_merge($this->groupMiddlewares, $middlewares);

        $this->routes[] = [
            'method' => $method,
            'path' => $fullPath,
            'handler' => $handler,
            'middlewares' => $allMiddlewares,
            'pattern' => $this->pathToPattern($fullPath)
        ];

        return $this;
    }

    private function pathToPattern(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $request->setParams($params);

                foreach ($route['middlewares'] as $middleware) {
                    $middlewareClass = "App\\Middleware\\{$middleware}";
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        $result = $middlewareInstance->handle($request);
                        if ($result === false) {
                            return;
                        }
                    }
                }

                $this->callHandler($route['handler'], $request);
                return;
            }
        }

        $this->notFound($request);
    }

    private function callHandler($handler, Request $request): void
    {
        if (is_callable($handler)) {
            call_user_func($handler, $request);
            return;
        }

        if (is_string($handler)) {
            list($controller, $method) = explode('@', $handler);
            $controllerClass = "App\\Controllers\\{$controller}";
            
            if (class_exists($controllerClass)) {
                $controllerInstance = new $controllerClass();
                call_user_func([$controllerInstance, $method], $request);
                return;
            }
        }

        $this->notFound($request);
    }

    private function notFound(Request $request): void
    {
        http_response_code(404);
        
        if ($request->expectsJson()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not Found', 'code' => 404]);
            return;
        }

        $controller = new \App\Controllers\ErrorController();
        $controller->notFound($request);
    }
}
