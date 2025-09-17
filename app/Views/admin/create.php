<h1 class="h1">Novo Contrato</h1>
<form method="post" action="/admin/contratos/criar" class="form">
  <input type="hidden" name="csrf" value="<?= $csrf ?>">
  <label>Empresa
    <input class="input" name="company" required>
  </label>
  <label>Local
    <input class="input" name="location" required>
  </label>
  <label>Status
    <select class="input" name="status">
      <option>Ativo</option>
      <option>Negociação</option>
      <option>Encerrado</option>
    </select>
  </label>
  <label>Notas
    <textarea class="input" name="notes" rows="4"></textarea>
  </label>
  <div class="flex gap">
    <button class="btn primary">Salvar</button>
    <a class="btn" href="/admin">Cancelar</a>
  </div>
</form>
