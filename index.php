<?php
declare(strict_types=1);
session_start();

// Autoload PSR-4 simples
spl_autoload_register(function ($class) {
  $map = [
    'App\\'  => __DIR__ . '/app/',
    'Core\\' => __DIR__ . '/core/',
  ];
  foreach ($map as $prefix => $base) {
    if (strncmp($class, $prefix, strlen($prefix)) === 0) {
      $rel = str_replace('\\', '/', substr($class, strlen($prefix)));
      $file = $base . $rel . '.php';
      if (is_file($file)) { require $file; }
    }
  }
});

use Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\InstallerController;

$router = new Router();

/** públicas */
$router->get('/',              [HomeController::class, 'index']);
$router->get('/login',         [AuthController::class, 'form']);
$router->post('/login',        [AuthController::class, 'login']);

/** instalador (apague após usar) */
$router->get('/install',       [InstallerController::class, 'run']);

/** middleware simples */
$authOnly = function ($handler) {
  return function (...$args) use ($handler) {
    if (empty($_SESSION['user'])) { header('Location: /login'); exit; }
    return is_array($handler) ? (new $handler[0])->{$handler[1]}(...$args) : $handler(...$args);
  };
};

/** admin */
$router->get('/admin',                         $authOnly([AdminController::class, 'index']));
$router->get('/admin/contratos/criar',         $authOnly([AdminController::class, 'createForm']));
$router->post('/admin/contratos/criar',        $authOnly([AdminController::class, 'create']));
$router->get('/admin/contratos/{id}/editar',   $authOnly([AdminController::class, 'editForm']));
$router->post('/admin/contratos/{id}/editar',  $authOnly([AdminController::class, 'update']));
$router->post('/admin/contratos/{id}/excluir', $authOnly([AdminController::class, 'delete']));
$router->post('/logout',                       $authOnly([AuthController::class, 'logout']));

/** 404 */
$router->fallback(function () { http_response_code(404); echo '404 - Página não encontrada'; });

$router->dispatch();
