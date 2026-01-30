<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);

require_once BASE_PATH . '/config/autoload.php';
require_once BASE_PATH . '/config/config.php';

use App\Core\Router;
use App\Core\Request;

$router = new Router();

require_once BASE_PATH . '/routes/web.php';
require_once BASE_PATH . '/routes/api.php';

$request = new Request();
$router->dispatch($request);
