<?php

require_once dirname(__DIR__) . '/app/config/app.php';
require_once APPROOT . '/core/Router.php';
require_once APPROOT . '/core/Database.php';
require_once APPROOT . '/core/Controller.php';

// Load Controllers, Models, and Core classes
spl_autoload_register(function($className){
    if (file_exists(APPROOT . '/controllers/' . $className . '.php')) {
        require_once APPROOT . '/controllers/' . $className . '.php';
    } elseif (file_exists(APPROOT . '/models/' . $className . '.php')) {
        require_once APPROOT . '/models/' . $className . '.php';
    } elseif (file_exists(APPROOT . '/core/' . $className . '.php')) {
        require_once APPROOT . '/core/' . $className . '.php';
    }
});

$router = new Router();

// Define routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('home', ['controller' => 'Home', 'action' => 'index']);
$router->add('register', ['controller' => 'Auth', 'action' => 'register']);
$router->add('login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('logout', ['controller' => 'Auth', 'action' => 'logout']);

// Client routes
$router->add('client/dashboard', ['controller' => 'Client', 'action' => 'dashboard']);
$router->add('client/new_case', ['controller' => 'Client', 'action' => 'newCase']);
$router->add('client/case_details/{id:\d+}', ['controller' => 'Client', 'action' => 'caseDetails']);
$router->add('client/consultations', ['controller' => 'Client', 'action' => 'consultations']);
$router->add('client/messages/{case_id:\d+}', ['controller' => 'Client', 'action' => 'messages']);

// Lawyer routes
$router->add('lawyer/dashboard', ['controller' => 'Lawyer', 'action' => 'dashboard']);
$router->add('lawyer/cases', ['controller' => 'Lawyer', 'action' => 'cases']);
$router->add('lawyer/consultations', ['controller' => 'Lawyer', 'action' => 'consultations']);
$router->add('lawyer/reports', ['controller' => 'Lawyer', 'action' => 'reports']);
$router->add('lawyer/archive', ['controller' => 'Lawyer', 'action' => 'archive']);

// Admin routes
$router->add('admin/dashboard', ['controller' => 'Admin', 'action' => 'dashboard']);
$router->add('admin/lawyers', ['controller' => 'Admin', 'action' => 'lawyers']);
$router->add('admin/cases', ['controller' => 'Admin', 'action' => 'cases']);
$router->add('admin/reports', ['controller' => 'Admin', 'action' => 'reports']);

// Get current URL
$url = $_SERVER['REQUEST_URI'];

// Remove query string if present
if (($pos = strpos($url, '?')) !== false) {
    $url = substr($url, 0, $pos);
}

// Remove the base directory from the URL if it exists
$script_name = $_SERVER['SCRIPT_NAME'];
$base_path = str_replace('index.php', '', $script_name);

if (strpos($url, $base_path) === 0) {
    $url = substr($url, strlen($base_path));
}

$url = trim($url, '/');

$router->dispatch($url);

?>
