<?php
namespace App\Core;

class View
{
    private static array $sections = [];
    private static array $sectionStack = [];
    private static ?string $layout = null;
    private static array $layoutData = [];

    public static function render(string $view, array $data = []): string
    {
        extract($data);
        
        ob_start();
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View not found: {$view}");
        }
        
        $content = ob_get_clean();

        if (self::$layout) {
            $layout = self::$layout;
            self::$layout = null;

            if (!isset(self::$sections['content'])) {
                self::$sections['content'] = $content;
            }
            
            return self::render($layout, array_merge($data, self::$layoutData));
        }

        return $content;
    }

    public static function extend(string $layout, array $data = []): void
    {
        self::$layout = $layout;
        self::$layoutData = $data;
    }

    public static function section(string $name): void
    {
        self::$sectionStack[] = $name;
        ob_start();
    }

    public static function endSection(): void
    {
        $name = array_pop(self::$sectionStack);
        self::$sections[$name] = ob_get_clean();
    }

    public static function yield(string $name, string $default = ''): string
    {
        return self::$sections[$name] ?? $default;
    }

    public static function include(string $view, array $data = []): void
    {
        extract($data);
        
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        }
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public static function asset(string $path): string
    {
        return APP_URL . '/assets/' . ltrim($path, '/');
    }

    public static function url(string $path = ''): string
    {
        return APP_URL . '/' . ltrim($path, '/');
    }

    public static function old(string $key, string $default = ''): string
    {
        return $_SESSION['_old'][$key] ?? $default;
    }

    public static function error(string $key): ?string
    {
        $errors = $_SESSION['_errors'][$key] ?? null;
        unset($_SESSION['_errors'][$key]);
        return $errors ? (is_array($errors) ? $errors[0] : $errors) : null;
    }

    public static function hasError(string $key): bool
    {
        return isset($_SESSION['_errors'][$key]);
    }

    public static function flash(string $key): ?string
    {
        $flash = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $flash;
    }

    public static function csrf(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return '<input type="hidden" name="_csrf_token" value="' . $_SESSION['_csrf_token'] . '">';
    }

    public static function method(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}
