<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

// Filtros
$status_filter = $_GET['status'] ?? '';
$type_filter = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';

// Construir query
$query = "SELECT * FROM licenses WHERE 1=1";
$params = [];

if ($status_filter) {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}
if ($type_filter) {
    $query .= " AND type LIKE ?";
    $params[] = "%$type_filter%";
}
if ($search) {
    $query .= " AND applicant_name LIKE ?";
    $params[] = "%$search%";
}

$query .= " ORDER BY submitted_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$licenses = $stmt->fetchAll();

// Tipos de licença para filtro
$stmt = $pdo->query("SELECT DISTINCT type FROM licenses");
$types = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Licenças - SGALM Matola</title>
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
.table thead { background-color: #0d6efd; color: #fff; }
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
            <li class="nav-item mb-2"><a href="#" class="nav-link active"><i class="fas fa-file-alt me-2"></i>Licenças</a></li>
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
                <span class="navbar-brand">Licenças</span>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item me-3"><i class="fas fa-bell"></i></li>
                        <li class="nav-item"><span class="nav-link">Olá, <?= esc($user['name']) ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <!-- Filtros -->
            <div class="card mb-4 p-3">
                <form method="get" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" value="<?= esc($search) ?>" class="form-control" placeholder="Pesquisar por requerente...">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="pendente" <?= $status_filter=='pendente'?'selected':'' ?>>Pendentes</option>
                            <option value="aprovado" <?= $status_filter=='aprovado'?'selected':'' ?>>Aprovadas</option>
                            <option value="recusado" <?= $status_filter=='recusado'?'selected':'' ?>>Recusadas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-select">
                            <option value="">Todos os tipos</option>
                            <?php foreach($types as $type): ?>
                                <option value="<?= esc($type) ?>" <?= $type_filter==$type?'selected':'' ?>><?= esc($type) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i>Filtrar</button>
                    </div>
                </form>
            </div>

            <!-- Tabela de Licenças -->
            <div class="card mb-4 p-3">
                <h6 class="card-title mb-3">Lista de Licenças</h6>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Requerente</th>
                                <th>NIF</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th>Submetido</th>
                                <th>Validade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($licenses as $lic): ?>
                            <tr>
                                <td><?= esc($lic['id']) ?></td>
                                <td><?= esc($lic['applicant_name']) ?></td>
                                <td><?= esc($lic['applicant_nif']) ?></td>
                                <td><?= esc($lic['type']) ?></td>
                                <td>
                                    <?php
                                    $status_class = 'badge-status ';
                                    if($lic['status']=='pendente') $status_class .= 'badge-pendente';
                                    if($lic['status']=='aprovado') $status_class .= 'badge-aprovado';
                                    if($lic['status']=='recusado') $status_class .= 'badge-recusado';
                                    ?>
                                    <span class="<?= $status_class ?>"><?= ucfirst($lic['status']) ?></span>
                                </td>
                                <td><?= esc($lic['submitted_at']) ?></td>
                                <td><?= esc($lic['expiry_date']) ?: '-' ?></td>
                                <td>
                                    <a href="license_view.php?id=<?= $lic['id'] ?>" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                    <?php if($lic['status']=='pendente' && $user['role']=='admin'): ?>
                                        <a href="license_approve.php?id=<?= $lic['id'] ?>&action=approve" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                                        <a href="license_approve.php?id=<?= $lic['id'] ?>&action=reject" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="footer mt-4">
                Desenvolvido por Artur Júnior | &copy; <?= date('Y') ?> SGALM - Matola
            </footer>
        </div>
    </div>
</div>
</body>
</html>
