<?php
namespace App\Controllers;
use Core\Controller;
use Core\Config;
use Core\DB;

class InstallerController extends Controller {
  public function run(): string {
    // proteja com token na URL: /install?token=SEU_TOKEN
    if (($_GET['token'] ?? '') !== Config::INSTALL_TOKEN) {
      http_response_code(403); return 'Forbidden';
    }
    $pdo = DB::pdo();
    // criar tabelas
    $pdo->exec("
      CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin','user') NOT NULL DEFAULT 'admin',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    $pdo->exec("
      CREATE TABLE IF NOT EXISTS contracts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        company VARCHAR(120) NOT NULL,
        location VARCHAR(60) NOT NULL,
        status ENUM('Ativo','Negociação','Encerrado') NOT NULL DEFAULT 'Ativo',
        notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    // seed admin se não existir
    $st = $pdo->query("SELECT COUNT(*) c FROM users");
    if ((int)$st->fetch()['c'] === 0) {
      $hash = password_hash('admin', PASSWORD_DEFAULT);
      $ins = $pdo->prepare("INSERT INTO users (username,password,role) VALUES (?,?, 'admin')");
      $ins->execute(['admin', $hash]);
    }
    return "<pre>OK: tabelas criadas/atualizadas. Usuário inicial: admin / admin
ATENÇÃO: remova/disable a rota /install e troque o INSTALL_TOKEN em core/Config.php.</pre>";
  }
}
