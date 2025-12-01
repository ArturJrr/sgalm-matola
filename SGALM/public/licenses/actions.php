<?php
// public/licenses/actions.php
require_once __DIR__ . '/../../config/functions.php';
require_login();
$user = current_user();

if($user['role'] !== 'admin'){
    if(!empty($_GET['ajax'])){ echo json_encode(['error'=>'Acesso negado']); exit; }
    echo 'Só administradores podem aprovar/recusar'; exit;
}

$action = $_GET['action'] ?? '';
$id = intval($_GET['id'] ?? 0);
if($action === 'approve'){
    $stmt = $pdo->prepare('UPDATE licenses SET status="aprovado", approved_by=?, approved_at=NOW() WHERE id=?');
    $stmt->execute([$user['id'],$id]);
    // obter dados da licença para notificação
    $lic = $pdo->prepare('SELECT * FROM licenses WHERE id=?'); $lic->execute([$id]); $lic = $lic->fetch();
    if($lic){
        // enviar email de notificação ao requerente se tiver email (campo NIF não é email); exemplo: enviar ao admin responsável
        // send_notification_email('responsavel@matola.gov.mz', 'Licença aprovada: #' . $id, 'A licença foi aprovada.');
    }
} elseif($action === 'reject'){
    $stmt = $pdo->prepare('UPDATE licenses SET status="recusado", approved_by=?, approved_at=NOW() WHERE id=?');
    $stmt->execute([$user['id'],$id]);
}

if(!empty($_GET['ajax'])){
    echo json_encode(['ok'=>true,'action'=>$action,'id'=>$id]);
    exit;
}
header('Location: list.php');
exit;
