<?php
// public/licenses/create.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!csrf_check($_POST['csrf'] ?? '')) {
        $errors[] = 'Token CSRF inválido.';
    } else {
        $app = trim($_POST['applicant_name'] ?? '');
        $nif = trim($_POST['applicant_nif'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $details = trim($_POST['details'] ?? '');

        if($app === '' || $type === '') $errors[] = 'Nome do requerente e tipo são obrigatórios.';

        $attachment = null;
        if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK){
            $tmp = $_FILES['attachment']['tmp_name'];
            $name = basename($_FILES['attachment']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['png','jpg','jpeg','pdf'];
            if(!in_array($ext, $allowed)){
                $errors[] = 'Tipo de ficheiro não permitido.';
            } else {
                $targetDir = __DIR__ . '/../uploads/';
                if(!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $newName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
                if(!move_uploaded_file($tmp, $targetDir . $newName)){
                    $errors[] = 'Falha ao guardar o anexo.';
                } else {
                    $attachment = $newName;
                }
            }
        }

        if(empty($errors)){
            $stmt = $pdo->prepare('INSERT INTO licenses (applicant_name, applicant_nif, type, details, attachment, created_by) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$app,$nif,$type,$details,$attachment,$user['id'] ?? null]);

            // enviar email de notificação simples (ex.: para um responsável)
            // send_notification_email('responsavel@matola.gov.mz', 'Nova Licença Submetida', 'Uma nova licença foi submetida.');

            header('Location: list.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><title>Nova Licença</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
<h1>Nova Licença</h1>
<?php if($errors): foreach($errors as $err): ?>
  <div class="alert alert-danger"><?= e($err) ?></div>
<?php endforeach; endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="col-md-6"><label class="form-label">Nome do requerente <input class="form-control" name="applicant_name" required></label></div>
  <div class="col-md-6"><label class="form-label">NIF <input class="form-control" name="applicant_nif"></label></div>
  <div class="col-md-6"><label class="form-label">Tipo <input class="form-control" name="type" required></label></div>
  <div class="col-12"><label class="form-label">Detalhes <textarea class="form-control" name="details"></textarea></label></div>
  <div class="col-md-6"><label class="form-label">Anexo (png/jpg/pdf) <input class="form-control" type="file" name="attachment"></label></div>
  <div class="col-12"><button class="btn btn-primary" type="submit">Submeter</button> <a href="list.php" class="btn btn-secondary">Voltar</a></div>
</form>
</body>
</html>
