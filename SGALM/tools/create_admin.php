<?php
// Incluir a configuração da base de dados
require_once __DIR__ . '/../config/db.php';

// Verifica se já existe um admin
$stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
$stmt->execute();
if($stmt->rowCount() > 0){
    echo "Administrador já existe!";
    exit;
}

// Dados do admin inicial
$name = "Administrador";
$email = "admin@matola.test";
$password = password_hash("Admin123!", PASSWORD_DEFAULT);
$role = "admin";

// Inserir admin na tabela users
$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role]);

echo "Administrador criado com sucesso: $email / Admin123!";
