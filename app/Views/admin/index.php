<h1 class="h1">Área Administrativa</h1>
<p class="muted">Gerencie seus contratos.</p>

<div class="mt">
  <a class="btn primary" href="/admin/contratos/criar">+ Novo contrato</a>
</div>

<section class="card mt">
  <div class="card-header">
    <h2 class="h2">Contratos</h2>
  </div>
  <div class="table">
    <div class="t-head">
      <div>Empresa</div><div>Local</div><div>Status</div><div>Ações</div>
    </div>
    <?php foreach ($contracts as $c): ?>
      <div class="t-row">
        <div><?= $this->e($c['company']) ?></div>
        <div><?= $this->e($c['location']) ?></div>
        <div><span class="badge"><?= $this->e($c['status']) ?></span></div>
        <div class="actions">
          <a class="btn" href="/admin/contratos/<?= (int)$c['id'] ?>/editar">Editar</a>
          <form method="post" action="/admin/contratos/<?= (int)$c['id'] ?>/excluir" class="inline" onsubmit="return confirm('Excluir este contrato?')">
            <input type="hidden" name="csrf" value="<?= $csrf ?>">
            <button class="btn">Excluir</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
