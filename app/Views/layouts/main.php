<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title ?? 'App') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="<?= \Core\Config::BASE_URL ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
<header class="topbar">
  <div class="container flex between">
    <a class="brand" href="/">BSM Contratos</a>
    <nav class="nav">
      <a href="/">Dashboard</a>
      <a href="/admin">Admin</a>
      <?php if (!empty($_SESSION['user'])): ?>
        <form method="post" action="/logout" class="inline">
          <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
          <button class="btn">Sair</button>
        </form>
      <?php else: ?>
        <a href="/login" class="btn">Entrar</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container py">
  <?= $content ?? '' ?>
</main>
<footer class="footer">
  <div class="container small">© <?= date('Y') ?> BSM Contratos</div>
</footer>
</body>
</html>
