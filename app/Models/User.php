<?php
namespace App\Models;
use Core\DB;
use PDO;

class User {
  public static function findByUsername(string $u): ?array {
    $st = DB::pdo()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $st->execute([$u]);
    $row = $st->fetch();
    return $row ?: null;
  }
}
