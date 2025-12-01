<?php
// public/licenses/edit.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM licenses WHERE id = ?');
$stmt->execute([$id]);
$it = $stmt->fetch();
if(!$it){ echo 'Não existe'; exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!csrf_check($_POST['csrf'] ?? '')) { $errors[] = 'Token CSRF inválido.'; }
    else {
        $app = trim($_POST['applicant_name'] ?? '');
        $nif = trim($_POST['applicant_nif'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $details = trim($_POST['details'] ?? '');
        $status = $_POST['status'] ?? 'pendente';
        $expiry = $_POST['expiry_date'] ?: null;

        $stmt = $pdo->prepare('UPDATE licenses SET applicant_name=?, applicant_nif=?, type=?, details=?, status=?, expiry_date=? WHERE id=?');
        $stmt->execute([$app,$nif,$type,$details,$status,$expiry,$id]);

        header('Location: list.php');
        exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Editar Licença</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h1>Editar Licença #<?= $it['id'] ?></h1>
<?php if($errors): foreach($errors as $err): ?>
  <div class="alert alert-danger"><?= e($err) ?></div>
<?php endforeach; endif; ?>
<form method="post" class="row g-3">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="col-md-6"><label class="form-label">Nome <input class="form-control" name="applicant_name" value="<?= e($it['applicant_name']) ?>" required></label></div>
  <div class="col-md-6"><label class="form-label">NIF <input class="form-control" name="applicant_nif" value="<?= e($it['applicant_nif']) ?>"></label></div>
  <div class="col-md-6"><label class="form-label">Tipo <input class="form-control" name="type" value="<?= e($it['type']) ?>" required></label></div>
  <div class="col-12"><label class="form-label">Detalhes <textarea class="form-control" name="details"><?= e($it['details']) ?></textarea></label></div>
  <div class="col-md-4">
    <label class="form-label">Status
      <select class="form-select" name="status">
        <option value="pendente" <?= $it['status']=='pendente'?'selected':'' ?>>pendente</option>
        <option value="aprovado" <?= $it['status']=='aprovado'?'selected':'' ?>>aprovado</option>
        <option value="recusado" <?= $it['status']=='recusado'?'selected':'' ?>>recusado</option>
      </select>
    </label>
  </div>
  <div class="col-md-4"><label class="form-label">Data expiração <input class="form-control" type="date" name="expiry_date" value="<?= e($it['expiry_date']) ?>"></label></div>
  <div class="col-12"><button class="btn btn-primary" type="submit">Guardar</button> <a href="list.php" class="btn btn-secondary">Voltar</a></div>
</form>
</body></html>
