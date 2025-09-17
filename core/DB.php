<?php
namespace Core;
use PDO, PDOException;

class DB {
  private static ?PDO $pdo = null;
  public static function pdo(): PDO {
    if (!self::$pdo) {
      $dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset='.Config::DB_CHARSET;
      self::$pdo = new PDO($dsn, Config::DB_USER, Config::DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ]);
    }
    return self::$pdo;
  }
}
