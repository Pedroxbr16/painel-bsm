<h1 class="h1">Entrar</h1>
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
<?php endif; ?>
<form method="post" action="/login" class="form">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
  <label>Usuário
    <input class="input" name="username" required>
  </label>
  <label>Senha
    <input class="input" type="password" name="password" required>
  </label>
  <button class="btn primary wfull">Entrar</button>
</form>
<p class="muted small">Demo inicial: admin / admin</p>
