<?php
// public/users/create.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'admin'){ echo 'Acesso negado'; exit; }

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!csrf_check($_POST['csrf'] ?? '')) $errors[] = 'Token CSRF inválido.';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'agente';

    if($name === '' || $email === '' || $password === '') $errors[] = 'Campos obrigatórios em falta.';
    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?,?,?,?)');
        $stmt->execute([$name,$email,$hash,$role]);
        header('Location: list.php');
        exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Novo Utilizador</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h1>Novo Utilizador</h1>
<?php if($errors): foreach($errors as $err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endforeach; endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-2"><label>Nome <input class="form-control" name="name" required></label></div>
  <div class="mb-2"><label>Email <input type="email" class="form-control" name="email" required></label></div>
  <div class="mb-2"><label>Senha <input class="form-control" type="password" name="password" required></label></div>
  <div class="mb-2"><label>Role <select class="form-select" name="role"><option value="agente">agente</option><option value="admin">admin</option></select></label></div>
  <button class="btn btn-primary" type="submit">Criar</button> <a href="list.php" class="btn btn-secondary">Voltar</a>
</form>
</body></html>
