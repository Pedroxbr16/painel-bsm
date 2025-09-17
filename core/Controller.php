<?php
namespace Core;

class Controller {
  protected function view(string $path, array $data=[]): string {
    $file = __DIR__ . '/../app/Views/' . $path . '.php';
    if (!is_file($file)) return "View [$path] não encontrada.";
    extract($data, EXTR_SKIP);
    ob_start(); include $file; return ob_get_clean();
  }
  protected function render(string $view, array $data=[]): string {
    $content = $this->view($view,$data);
    return $this->view('layouts/main', ['content'=>$content, 'title'=>$data['title'] ?? null]);
  }
  protected function csrf(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
  }
  protected function checkCsrf(): void {
    $ok = !empty($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
    if (!$ok) { http_response_code(419); exit('CSRF inválido'); }
  }
  protected function redirect(string $to){ header("Location: $to"); exit; }
  protected function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
}
