<?php
session_start();

// Hỗ trợ Built-in server của PHP để render file tĩnh (css, js, ảnh)
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    if ($path !== '/' && file_exists(__DIR__ . $path)) {
        return false; // Phục vụ file tĩnh trực tiếp
    }
}

require_once 'config/database.php';
require_once 'core/Auth.php';
require_once 'core/BaseController.php';
require_once 'core/BaseModel.php';
require_once 'core/Router.php';

$router = new Router();
require_once 'config/routes.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
?>
