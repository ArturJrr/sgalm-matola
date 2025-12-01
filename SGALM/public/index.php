<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

// Força login
require_login();
$user = current_user();

// Estatísticas gerais
$stmt = $pdo->query("
    SELECT 
        COUNT(*) as total,
        SUM(status='pendente') as pendente,
        SUM(status='aprovado') as aprovado,
        SUM(status='recusado') as recusado,
        SUM(expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)) as expirando
    FROM licenses
");
$stats = $stmt->fetch();

// Últimas licenças cadastradas
$stmt = $pdo->query("SELECT * FROM licenses ORDER BY submitted_at DESC LIMIT 10");
$recent_licenses = $stmt->fetchAll();

// Licenças por tipo para o gráfico de pizza
$stmt = $pdo->query("SELECT type, COUNT(*) AS total FROM licenses GROUP BY type");
$licenses_by_type = $stmt->fetchAll();

// Histórico mensal (últimos 12 meses)
$hist_data = [];
for($i=11;$i>=0;$i--){
    $month = date('Y-m', strtotime("-$i month"));
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM licenses WHERE DATE_FORMAT(submitted_at,'%Y-%m') = ?");
    $stmt->execute([$month]);
    $hist_data[] = $stmt->fetchColumn();
}
$hist_labels = [];
for($i=11;$i>=0;$i--){
    $hist_labels[] = date('M Y', strtotime("-$i month"));
}

// Total de usuários
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$total_users = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard SGALM - Matola</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
}
.sidebar {
    min-height: 100vh;
    background-color: #0d6efd;
    color: white;
}
.sidebar a {
    color: white;
    text-decoration: none;
}
.sidebar a:hover { background-color: #0b5ed7; }
.navbar { box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
.card { border-radius: 12px; transition: 0.3s; }
.card:hover { transform: translateY(-3px); box-shadow: 0 12px 20px rgba(0,0,0,0.15); }
.table thead { background-color: #0d6efd; color: #fff; }
.footer { text-align: center; margin-top: 2rem; color: #777; }
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
            <li class="nav-item mb-2"><a href="#" class="nav-link"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link"><i class="fas fa-file-alt me-2"></i>Licenças</a></li>
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
                <span class="navbar-brand">Dashboard</span>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item me-3"><i class="fas fa-bell"></i> <span class="badge bg-danger"><?= $stats['pendente'] ?></span></li>
                        <li class="nav-item"><span class="nav-link">Olá, <?= esc($user['name']) ?></span></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            <!-- Cards Estatísticas -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card text-center p-3">
                        <h6>Total Licenças</h6>
                        <h3><?= $stats['total'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center p-3">
                        <h6>Pendentes</h6>
                        <h3><?= $stats['pendente'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center p-3">
                        <h6>Aprovadas</h6>
                        <h3><?= $stats['aprovado'] ?></h3>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-center p-3">
                        <h6>Recusadas</h6>
                        <h3><?= $stats['recusado'] ?></h3>
                    </div>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Licenças por Status</h6>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Licenças por Tipo</h6>
                        <canvas id="typeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <div class="card p-3">
                        <h6 class="card-title">Últimas Licenças Cadastradas</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Requerente</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Submetido</th>
                                        <th>Validade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recent_licenses as $lic): ?>
                                    <tr>
                                        <td><?= esc($lic['id']) ?></td>
                                        <td><?= esc($lic['applicant_name']) ?></td>
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
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sobre o Sistema -->
            <div class="card mb-4">
                <div class="card-header">Sobre o Sistema</div>
                <div class="card-body">
                    <p>O SGALM (Sistema de Gestão de Licenças Municipais) do Município da Matola permite o gerenciamento completo de licenças comerciais e de obras, garantindo transparência e eficiência administrativa.</p>
                    <ul>
                        <li>Cadastro e aprovação de licenças</li>
                        <li>Controle de status (pendente, aprovado, recusado)</li>
                        <li>Dashboard com gráficos e estatísticas</li>
                        <li>Histórico completo de solicitações</li>
                        <li>Gestão de usuários e permissões</li>
                        <li>Alertas de licenças expirando</li>
                    </ul>
                </div>
            </div>

            <footer class="footer">
                Desenvolvido por Artur Júnior | &copy; <?= date('Y') ?> SGALM - Matola
            </footer>
        </div>
    </div>
</div>

<!-- Charts JS -->
<script>
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'bar',
    data: {
        labels: ['Pendentes','Aprovadas','Recusadas'],
        datasets: [{
            label: 'Licenças',
            data: [<?= $stats['pendente'] ?>, <?= $stats['aprovado'] ?>, <?= $stats['recusado'] ?>],
            backgroundColor: ['#ffc107','#198754','#dc3545']
        }]
    },
    options: { responsive:true, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

const typeCtx = document.getElementById('typeChart').getContext('2d');
const typeChart = new Chart(typeCtx, {
    type: 'pie',
    data: {
        labels: [<?php foreach($licenses_by_type as $lt) echo "'".esc($lt['type'])."',"; ?>],
        datasets: [{
            data: [<?php foreach($licenses_by_type as $lt) echo $lt['total'].','; ?>],
            backgroundColor: ['#0d6efd','#6f42c1','#198754','#fd7e14','#dc3545','#20c997']
        }]
    },
    options: { responsive:true }
});
</script>
</body>
</html>
