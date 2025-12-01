<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

// Obter ID da licença
$id = $_GET['id'] ?? null;
if(!$id){
    header('Location: licenses.php');
    exit;
}

// Buscar licença
$stmt = $pdo->prepare("SELECT l.*, u.name as creator_name, a.name as approver_name 
                       FROM licenses l 
                       LEFT JOIN users u ON l.created_by = u.id
                       LEFT JOIN users a ON l.approved_by = a.id
                       WHERE l.id = ?");
$stmt->execute([$id]);
$license = $stmt->fetch();

if(!$license){
    die("Licença não encontrada.");
}

// Processar ações rápidas de aprovação/recusa
if(isset($_GET['action']) && $user['role']=='admin'){
    $action = $_GET['action'];
    if($action=='approve' || $action=='reject'){
        $new_status = $action=='approve' ? 'aprovado' : 'recusado';
        $stmt = $pdo->prepare("UPDATE licenses SET status=?, approved_by=?, approved_at=NOW() WHERE id=?");
        $stmt->execute([$new_status, $user['id'], $id]);
        header("Location: license_view.php?id=$id");
        exit;
    }
}

// Badge de status
$status_class = 'badge-status ';
if($license['status']=='pendente') $status_class .= 'badge-pendente';
if($license['status']=='aprovado') $status_class .= 'badge-aprovado';
if($license['status']=='recusado') $status_class .= 'badge-recusado';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Visualizar Licença - SGALM</title>
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
.badge-status { font-weight: 500; padding: 0.4em 0.6em; border-radius: 6px; }
.badge-pendente { background-color: #ffc107; color: #212529; }
.badge-aprovado { background-color: #198754; }
.badge-recusado { background-color: #dc3545; }
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
                <span class="navbar-brand">Visualizar Licença</span>
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
                <h5 class="mb-3">Licença #<?= esc($license['id']) ?></h5>
                <div class="mb-3">
                    <span class="<?= $status_class ?>"><?= ucfirst($license['status']) ?></span>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Requerente:</strong> <?= esc($license['applicant_name']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>NIF:</strong> <?= esc($license['applicant_nif']) ?: '-' ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Tipo:</strong> <?= esc($license['type']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Submetido em:</strong> <?= esc($license['submitted_at']) ?>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Detalhes:</strong>
                    <p><?= nl2br(esc($license['details'])) ?></p>
                </div>
                <div class="mb-3">
                    <strong>Anexo:</strong>
                    <?php if($license['attachment']): ?>
                        <a href="../uploads/<?= esc($license['attachment']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf me-1"></i>Visualizar Anexo</a>
                    <?php else: ?>
                        <span>-</span>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <strong>Criado por:</strong> <?= esc($license['creator_name'] ?: '-') ?>
                </div>
                <div class="mb-3">
                    <strong>Aprovado por:</strong> <?= esc($license['approver_name'] ?: '-') ?>
                    <?php if($license['approved_at']): ?>
                        <span>(<?= esc($license['approved_at']) ?>)</span>
                    <?php endif; ?>
                </div>

                <!-- Ações rápidas -->
                <?php if($license['status']=='pendente' && $user['role']=='admin'): ?>
                    <div class="mt-3">
                        <a href="?id=<?= $license['id'] ?>&action=approve" class="btn btn-success me-2"><i class="fas fa-check me-1"></i>Aprovar</a>
                        <a href="?id=<?= $license['id'] ?>&action=reject" class="btn btn-danger"><i class="fas fa-times me-1"></i>Recusar</a>
                    </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="licenses.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Voltar para Licenças</a>
                </div>
            </div>
        </div>

        <footer class="footer mt-4 text-center">
            Desenvolvido por Artur Júnior | &copy; <?= date('Y') ?> SGALM - Matola
        </footer>
    </div>
</div>
</body>
</html>
