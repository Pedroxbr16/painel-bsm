<h1 class="h1">Dashboard de Contratos</h1>

<section class="grid-3 gap">
  <?php foreach ($stats as $s): ?>
    <div class="card">
      <div class="stat"><?= (int)$s['value'] ?></div>
      <div class="muted"><?= $this->e($s['label']) ?></div>
    </div>
  <?php endforeach; ?>
</section>

<section class="card mt">
  <div class="card-header">
    <h2 class="h2">Últimos contratos</h2>
  </div>
  <div class="table">
    <div class="t-head"><div>Empresa</div><div>Local</div><div>Status</div></div>
    <?php foreach ($contracts as $c): ?>
      <div class="t-row">
        <div><?= $this->e($c['company']) ?></div>
        <div><?= $this->e($c['location']) ?></div>
        <div><span class="badge"><?= $this->e($c['status']) ?></span></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
