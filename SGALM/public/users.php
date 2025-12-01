<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

// Apenas admins podem gerenciar usuários
if($user['role'] !== 'admin'){
    die("Acesso negado. Apenas administradores podem acessar esta página.");
}

// Processar criação de novo usuário
$success = $error = null;
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])){
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'agente';

    if($name && $email && $password){
        // Verificar se email já existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if($stmt->fetch()){
            $error = "Email já cadastrado.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password,role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, password_hash($password,PASSWORD_DEFAULT), $role]);
            $success = "Usuário criado com sucesso!";
        }
    } else {
        $error = "Preencha todos os campos obrigatórios.";
    }
}

// Buscar todos usuários
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Gestão de Usuários - SGALM</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
.sidebar { min-height: 100vh; background-color: #0d6efd; color: white; }
.sidebar a { color: white; text-decoration: none; }
.sidebar a:hover { background-color: #0b5ed7; }
.navbar { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.card { border-radius: 12px; transition: 0.3s; }
.card:hover { transform: translateY(-3px); box-shadow: 0 12px 20px rgba(0,0,0,0.15); }
</style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar p-3 flex-shrink-0" style="width: 220px;">
        <h4 class="text-center mb-4">SGALM</h4>
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="index.php" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
            <li class="nav-item mb-2"><a href="licenses.php" class="nav-link"><i class="fas fa-file-alt me-2"></i>Licenças</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link"><i class="fas fa-chart-line me-2"></i>Relatórios</a></li>
            <li class="nav-item mb-2"><a href="users.php" class="nav-link active"><i class="fas fa-users me-2"></i>Usuários</a></li>
            <li class="nav-item mb-2"><a href="settings.php" class="nav-link"><i class="fas fa-cogs me-2"></i>Configurações</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
            <div class="container-fluid">
                <span class="navbar-brand">Gestão de Usuários</span>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item"><span class="nav-link">Olá, <?= esc($user['name']) ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <div class="card p-4 mb-4">
                <h4 class="mb-3">Criar Novo Usuário</h4>
                <?php if($success): ?>
                    <div class="alert alert-success"><?= esc($success) ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= esc($error) ?></div>
                <?php endif; ?>
                <form method="post">
                    <input type="hidden" name="create_user" value="1">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Função</label>
                            <select name="role" class="form-select">
                                <option value="agente">Agente</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-user-plus me-1"></i>Criar Usuário</button>
                </form>
            </div>

            <div class="card p-4">
                <h4 class="mb-3">Lista de Usuários</h4>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Função</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                            <tr>
                                <td><?= esc($u['id']) ?></td>
                                <td><?= esc($u['name']) ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td><?= ucfirst(esc($u['role'])) ?></td>
                                <td><?= esc($u['created_at']) ?></td>
                                <td>
                                    <!-- Ações rápidas -->
                                    <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                    <a href="user_delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja remover este usuário?');"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(count($users)==0): ?>
                            <tr><td colspan="6" class="text-center">Nenhum usuário encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="footer mt-4 text-center">
            Desenvolvido por Artur Júnior | &copy; <?= date('Y') ?> SGALM - Matola
        </footer>
    </div>
</div>
</body>
</html>
