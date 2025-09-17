<?php
namespace App\Controllers;
use Core\Controller;
use App\Models\User;

class AuthController extends Controller {
  public function form(): string {
    if (!empty($_SESSION['user'])) $this->redirect('/admin');
    return $this->render('auth/login', ['title'=>'Entrar', 'csrf'=>$this->csrf()]);
  }

  public function login(): void {
    $this->checkCsrf();
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    $user = User::findByUsername($u);
    if ($user && password_verify($p, $user['password'])) {
      $_SESSION['user'] = ['id'=>$user['id'], 'username'=>$user['username'], 'role'=>$user['role']];
      $this->redirect('/admin');
    }
    $_SESSION['flash'] = 'Credenciais inválidas';
    $this->redirect('/login');
  }

  public function logout(): void {
    $this->checkCsrf();
    unset($_SESSION['user']);
    $this->redirect('/');
  }
}
