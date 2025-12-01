<?php
// public/licenses/view.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT l.*, u.name as approver FROM licenses l LEFT JOIN users u ON l.approved_by = u.id WHERE l.id = ?');
$stmt->execute([$id]);
$it = $stmt->fetch();
if(!$it){ echo 'Não encontrado'; exit; }
?>
<!doctype html><html><head><meta charset="utf-8"><title>Ver Licença</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h1>Licença #<?= $it['id'] ?></h1>
<p><strong>Requerente:</strong> <?= e($it['applicant_name']) ?></p>
<p><strong>NIF:</strong> <?= e($it['applicant_nif']) ?></p>
<p><strong>Tipo:</strong> <?= e($it['type']) ?></p>
<p><strong>Detalhes:</strong> <?= nl2br(e($it['details'])) ?></p>
<p><strong>Status:</strong> <?= e($it['status']) ?></p>
<p><strong>Aprovado por:</strong> <?= e($it['approver'] ?? '-') ?></p>

<h4>Anexo</h4>
<?php if($it['attachment'] && file_exists(__DIR__ . '/../uploads/' . $it['attachment'])): 
    $file = '../uploads/' . $it['attachment'];
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if(in_array($ext, ['png','jpg','jpeg'])): ?>
      <img src="<?= e($file) ?>" alt="anexo" style="max-width:400px;">
    <?php elseif($ext === 'pdf'): ?>
      <a class="btn btn-outline-secondary" href="<?= e($file) ?>" target="_blank">Abrir PDF</a>
    <?php endif;
else: ?>
  <div class="text-muted">Sem anexo.</div>
<?php endif; ?>

<p><a href="list.php" class="btn btn-secondary mt-3">Voltar</a></p>
</body></html>
