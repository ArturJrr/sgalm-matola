<?php
// public/users/list.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();
if($user['role'] !== 'admin'){ echo 'Acesso negado'; exit; }

$q = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY id DESC');
$items = $q->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Utilizadores</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="container py-4">
<h1>Utilizadores</h1>
<p><a href="../index.php" class="btn btn-light">Voltar</a> <a href="create.php" class="btn btn-primary">Novo utilizador</a></p>
<table class="table table-striped">
<thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Role</th><th>Criado</th></tr></thead>
<tbody>
<?php foreach($items as $it): ?>
<tr><td><?= $it['id'] ?></td><td><?= e($it['name']) ?></td><td><?= e($it['email']) ?></td><td><?= e($it['role']) ?></td><td><?= $it['created_at'] ?></td></tr>
<?php endforeach; ?>
</tbody></table>
</body></html>
