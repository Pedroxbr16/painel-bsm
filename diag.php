<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php-error.log');

header('Content-Type: text/plain; charset=utf-8');

echo "== DIAGNOSTIC ==\n";
echo "PHP: " . PHP_VERSION . "\n";
echo "ext/pdo: " . (extension_loaded('pdo') ? 'yes' : 'NO') . "\n";
echo "ext/pdo_mysql: " . (extension_loaded('pdo_mysql') ? 'yes' : 'NO') . "\n\n";

$paths = ['core/Config.php','core/DB.php','app/Controllers/InstallerController.php','index.php','.htaccess'];
foreach ($paths as $p) {
  echo sprintf("%-40s %s\n", $p, is_file(__DIR__.'/'.$p) ? 'OK' : 'MISSING');
}
echo "\n";

// autoload igual ao index.php
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

echo "Classes: ";
echo class_exists('Core\\Config') ? 'Config ' : '';
echo class_exists('Core\\DB') ? 'DB ' : '';
echo class_exists('App\\Controllers\\InstallerController') ? 'InstallerController ' : '';
echo "\n\n";

// Teste de conexão ao MySQL (NÃO imprime a senha)
try {
  $ref = new ReflectionClass('Core\\Config');
  $dbName = $ref->getConstant('DB_NAME');
  $dbUser = $ref->getConstant('DB_USER');
  $dbHost = $ref->getConstant('DB_HOST');
  $dbPass = $ref->getConstant('DB_PASS'); // só usa, não imprime
  $charset = $ref->getConstant('DB_CHARSET');

  $dsn = "mysql:host={$dbHost};dbname={$dbName};charset={$charset}";
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  echo "MySQL: OK (conectou em {$dbHost}, db={$dbName})\n";
} catch (Throwable $e) {
  echo "MySQL: ERRO -> " . $e->getMessage() . "\n";
}

echo "\nDone. (Veja também php-error.log se existir)\n";
