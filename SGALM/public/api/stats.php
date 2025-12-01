<?php
// public/api/stats.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/functions.php';
require_login();

$total = (int)$pdo->query('SELECT COUNT(*) as c FROM licenses')->fetch()['c'];
$pend = (int)$pdo->query("SELECT COUNT(*) as c FROM licenses WHERE status='pendente'")->fetch()['c'];
$aprov = (int)$pdo->query("SELECT COUNT(*) as c FROM licenses WHERE status='aprovado'")->fetch()['c'];
$rec = (int)$pdo->query("SELECT COUNT(*) as c FROM licenses WHERE status='recusado'")->fetch()['c'];

echo json_encode(['total'=>$total,'pendente'=>$pend,'aprovado'=>$aprov,'recusado'=>$rec]);
