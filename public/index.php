<?php
/**
 * Front Controller
 * 
 * @developer   Jackisa Daniel Barack
 * @email       barackdanieljackisa@gmail.com
 * @website     jackisa.com
 * @quote       "One man and God are Majority"
 * @rights      All rights reserved
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require_once BASE_PATH . '/config/autoload.php';
    require_once BASE_PATH . '/config/config.php';

    use App\Core\Router;
    use App\Core\Request;

    $router = new Router();

    require_once BASE_PATH . '/routes/web.php';
    require_once BASE_PATH . '/routes/api.php';

    $request = new Request();
    $router->dispatch($request);
} catch (\Throwable $e) {
    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo "<h1>Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        http_response_code(500);
        echo "An error occurred. Please try again later.";
    }
}
