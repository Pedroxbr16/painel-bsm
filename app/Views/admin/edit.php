<h1 class="h1">Editar Contrato</h1>
<form method="post" action="/admin/contratos/<?= (int)$c['id'] ?>/editar" class="form">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
  <label>Empresa
    <input class="input" name="company" value="<?= $this->e($c['company']) ?>" required>
  </label>
  <label>Local
    <input class="input" name="location" value="<?= $this->e($c['location']) ?>" required>
  </label>
  <label>Status
    <select class="input" name="status">
      <?php foreach (['Ativo','Negociação','Encerrado'] as $s): ?>
        <option <?= $c['status']===$s?'selected':'' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Notas
    <textarea class="input" name="notes" rows="4"><?= $this->e($c['notes'] ?? '') ?></textarea>
  </label>
  <div class="flex gap">
    <button class="btn primary">Salvar</button>
    <a class="btn" href="/admin">Cancelar</a>
  </div>
</form>
