<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php-error.log');

session_start();

// autoload
spl_autoload_register(function ($class) {
  $map = ['App\\' => __DIR__ . '/app/', 'Core\\' => __DIR__ . '/core/'];
  foreach ($map as $prefix => $base) {
    if (strncmp($class, $prefix, strlen($prefix)) === 0) {
      $rel = str_replace('\\', '/', substr($class, strlen($prefix)));
      $file = $base . $rel . '.php';
      if (is_file($file)) require $file;
    }
  }
});

use App\Controllers\InstallerController;
use Core\Config;

try {
  if (($_GET['token'] ?? '') !== Config::INSTALL_TOKEN) {
    http_response_code(403);
    exit('Forbidden (token inválido)');
  }
  echo (new InstallerController())->run();
} catch (Throwable $e) {
  http_response_code(500);
  echo "INSTALL ERROR: " . $e->getMessage();
}
