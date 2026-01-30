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

// FORCE error display - ALWAYS show errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

// Show fatal errors that bypass try/catch (e.g. E_ERROR)
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE], true)) {
        http_response_code(500);
        echo "<h1>Fatal Error</h1>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($error['message']) . "</p>";
        echo "<p><strong>File:</strong> " . htmlspecialchars($error['file']) . " on line " . (int)$error['line'] . "</p>";
    }
});

try {
    require_once BASE_PATH . '/config/autoload.php';
    require_once BASE_PATH . '/config/config.php';

    $router = new \App\Core\Router();

    require_once BASE_PATH . '/routes/web.php';
    require_once BASE_PATH . '/routes/api.php';

    $request = new \App\Core\Request();
    $router->dispatch($request);
} catch (\Throwable $e) {
    echo "<h1>Error</h1>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
