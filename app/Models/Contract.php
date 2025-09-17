<?php
namespace App\Models;
use Core\DB;

class Contract {
  public static function stats(): array {
    $pdo = DB::pdo();
    $total     = $pdo->query("SELECT COUNT(*) c FROM contracts")->fetch()['c'] ?? 0;
    $ativos    = $pdo->query("SELECT COUNT(*) c FROM contracts WHERE status='Ativo'")->fetch()['c'] ?? 0;
    $neg       = $pdo->query("SELECT COUNT(*) c FROM contracts WHERE status='Negociação'")->fetch()['c'] ?? 0;
    $enc       = $pdo->query("SELECT COUNT(*) c FROM contracts WHERE status='Encerrado'")->fetch()['c'] ?? 0;
    return compact('total','ativos','neg','enc');
  }

  public static function all(): array {
    return DB::pdo()->query("SELECT * FROM contracts ORDER BY created_at DESC")->fetchAll();
  }

  public static function find(int $id): ?array {
    $st = DB::pdo()->prepare("SELECT * FROM contracts WHERE id=?");
    $st->execute([$id]);
    $row = $st->fetch();
    return $row ?: null;
  }

  public static function create(array $data): int {
    $st = DB::pdo()->prepare("INSERT INTO contracts (company, location, status, notes) VALUES (?,?,?,?)");
    $st->execute([$data['company'],$data['location'],$data['status'],$data['notes'] ?? null]);
    return (int)DB::pdo()->lastInsertId();
  }

  public static function update(int $id, array $data): void {
    $st = DB::pdo()->prepare("UPDATE contracts SET company=?, location=?, status=?, notes=? WHERE id=?");
    $st->execute([$data['company'],$data['location'],$data['status'],$data['notes'] ?? null, $id]);
  }

  public static function delete(int $id): void {
    $st = DB::pdo()->prepare("DELETE FROM contracts WHERE id=?");
    $st->execute([$id]);
  }
}
