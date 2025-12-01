<?php
// public/licenses/list.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();

$q = $pdo->query('SELECT l.*, u.name as approver FROM licenses l LEFT JOIN users u ON l.approved_by = u.id ORDER BY l.submitted_at DESC');
$items = $q->fetchAll();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Licenças</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h1>Licenças</h1>
<p><a href="../index.php" class="btn btn-light">Voltar</a> <a href="create.php" class="btn btn-primary">Nova Licença</a></p>
<table class="table table-striped">
  <thead><tr><th>ID</th><th>Requerente</th><th>Tipo</th><th>Status</th><th>Submetido</th><th>Ações</th></tr></thead>
  <tbody>
  <?php foreach($items as $it): ?>
    <tr id="row-<?= $it['id'] ?>">
      <td><?= $it['id'] ?></td>
      <td><?= e($it['applicant_name']) ?></td>
      <td><?= e($it['type']) ?></td>
      <td class="status"><?= e($it['status']) ?></td>
      <td><?= $it['submitted_at'] ?></td>
      <td>
        <a class="btn btn-sm btn-outline-secondary" href="view.php?id=<?= $it['id'] ?>">Ver</a>
        <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $it['id'] ?>">Editar</a>
        <?php if($user['role'] === 'admin'): ?>
          <button class="btn btn-sm btn-success ajax-act" data-action="approve" data-id="<?= $it['id'] ?>">Aprovar</button>
          <button class="btn btn-sm btn-danger ajax-act" data-action="reject" data-id="<?= $it['id'] ?>">Recusar</button>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<script>
document.querySelectorAll('.ajax-act').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    const action = btn.dataset.action;
    const id = btn.dataset.id;
    if(!confirm('Confirma ' + action + ' da licença #' + id + '?')) return;
    const res = await fetch('actions.php?action=' + action + '&id=' + id + '&ajax=1', { credentials: 'same-origin' });
    const data = await res.json();
    if(data.ok){
      const row = document.getElementById('row-' + id);
      row.querySelector('.status').textContent = action === 'approve' ? 'aprovado' : 'recusado';
      btn.disabled = true;
    } else {
      alert('Erro: ' + (data.error || 'desconhecido'));
    }
  });
});
</script>
</body></html>
