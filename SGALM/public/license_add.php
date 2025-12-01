<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

$error = null;
$success = null;

// Processar envio do formulário
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_name = trim($_POST['applicant_name'] ?? '');
    $applicant_nif = trim($_POST['applicant_nif'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $details = trim($_POST['details'] ?? '');
    $attachment = null;

    // Upload do anexo
    if(isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK){
        $upload_dir = __DIR__ . '/../uploads/';
        if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = time() . '_' . basename($_FILES['attachment']['name']);
        $filepath = $upload_dir . $filename;
        if(move_uploaded_file($_FILES['attachment']['tmp_name'], $filepath)){
            $attachment = $filename;
        } else {
            $error = "Falha ao enviar anexo.";
        }
    }

    if(!$error){
        $stmt = $pdo->prepare("INSERT INTO licenses 
            (applicant_name, applicant_nif, type, details, attachment, created_by) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$applicant_name, $applicant_nif, $type, $details, $attachment, $user['id']]);
        $success = "Licença cadastrada com sucesso!";
    }
}

// Tipos de licença para seleção
$license_types = ['Licença Comercial','Licença de Obras','Licença Ambiental','Outro'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Adicionar Licença - SGALM</title>
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
            <li class="nav-item mb-2"><a href="#" class="nav-link"><i class="fas fa-users me-2"></i>Usuários</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link"><i class="fas fa-cogs me-2"></i>Configurações</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white px-4">
            <div class="container-fluid">
                <span class="navbar-brand">Nova Licença</span>
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
                <h5 class="mb-3">Cadastrar Nova Licença</h5>
                <?php if($error): ?>
                    <div class="alert alert-danger"><?= esc($error) ?></div>
                <?php endif; ?>
                <?php if($success): ?>
                    <div class="alert alert-success"><?= esc($success) ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome do Requerente</label>
                        <input type="text" name="applicant_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NIF</label>
                        <input type="text" name="applicant_nif" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Licença</label>
                        <select name="type" class="form-select" required>
                            <?php foreach($license_types as $type): ?>
                                <option value="<?= esc($type) ?>"><?= esc($type) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Anexo (PDF/Imagem)</label>
                        <input type="file" name="attachment" class="form-control" accept=".pdf,.jpg,.png,.jpeg">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Detalhes</label>
                        <textarea name="details" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Cadastrar Licença</button>
                        <a href="licenses.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
                    </div>
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
