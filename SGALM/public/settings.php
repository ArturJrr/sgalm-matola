<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

// Apenas admins podem acessar configurações
if($user['role'] !== 'admin'){
    die("Acesso negado. Apenas administradores podem acessar esta página.");
}

// Processar atualização de configurações
$success = $error = null;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $system_name = $_POST['system_name'] ?? '';
    $contact_email = $_POST['contact_email'] ?? '';
    $contact_phone = $_POST['contact_phone'] ?? '';
    
    if($system_name && $contact_email){
        $stmt = $pdo->prepare("UPDATE settings 
                               SET system_name = ?, contact_email = ?, contact_phone = ? 
                               WHERE id = 1");
        $stmt->execute([$system_name, $contact_email, $contact_phone]);
        $success = "Configurações atualizadas com sucesso!";
    } else {
        $error = "Por favor, preencha os campos obrigatórios.";
    }
}

// Buscar configurações atuais
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Configurações - SGALM</title>
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
            <li class="nav-item mb-2"><a href="users.php" class="nav-link"><i class="fas fa-users me-2"></i>Usuários</a></li>
            <li class="nav-item mb-2"><a href="settings.php" class="nav-link active"><i class="fas fa-cogs me-2"></i>Configurações</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
            <div class="container-fluid">
                <span class="navbar-brand">Configurações do Sistema</span>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item"><span class="nav-link">Olá, <?= esc($user['name']) ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <div class="card p-4">
                <h4 class="mb-4">Configurações Gerais</h4>

                <?php if($success): ?>
                    <div class="alert alert-success"><?= esc($success) ?></div>
                <?php endif; ?>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= esc($error) ?></div>
                <?php endif; ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Nome do Sistema</label>
                        <input type="text" name="system_name" class="form-control" value="<?= esc($settings['system_name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email de Contato</label>
                        <input type="email" name="contact_email" class="form-control" value="<?= esc($settings['contact_email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefone de Contato</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?= esc($settings['contact_phone']) ?>">
                    </div>
                    <button class="btn btn-primary" type="submit"><i class="fas fa-save me-1"></i>Salvar Configurações</button>
                </form>
            </div>
        </div>

        <footer class="footer mt-4 text-center">
            Desenvolvido por Artur Júnior | &copy; <?= date('Y') ?> SGALM - Matola
        </footer>
    </div>
</div>
</body>
</html>
