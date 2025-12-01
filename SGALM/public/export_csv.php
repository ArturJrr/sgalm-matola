<?php
// public/export_csv.php
require_once __DIR__ . '/config/functions.php';
require_login();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=licencas.csv');

$out = fopen('php://output', 'w');
fputcsv($out, ['id','applicant_name','type','status','submitted_at','expiry_date']);
$q = $pdo->query('SELECT id, applicant_name, type, status, submitted_at, expiry_date FROM licenses ORDER BY id DESC');
while($r = $q->fetch()){
    fputcsv($out, [$r['id'],$r['applicant_name'],$r['type'],$r['status'],$r['submitted_at'],$r['expiry_date']]);
}
fclose($out);
exit;
