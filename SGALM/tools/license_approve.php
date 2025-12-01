<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

require_login();
$user = current_user();

// Apenas admins podem aprovar/recusar
if($user['role'] !== 'admin'){
    die("Acesso negado. Apenas administradores podem executar esta ação.");
}

// Obter ID e ação
$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

if(!$id || !$action){
    die("Parâmetros inválidos.");
}

// Validar ação
if(!in_array($action, ['approve','reject'])){
    die("Ação inválida.");
}

// Determinar novo status
$new_status = $action === 'approve' ? 'aprovado' : 'recusado';

// Atualizar licença no banco
$stmt = $pdo->prepare("UPDATE licenses 
                       SET status = ?, approved_by = ?, approved_at = NOW() 
                       WHERE id = ?");
$stmt->execute([$new_status, $user['id'], $id]);

// Redirecionar de volta para visualização da licença
header("Location: ../public/license_view.php?id=$id");
exit;
