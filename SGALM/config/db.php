<?php
$DB_HOST = "sql100.infinityfree.com";   // Host fornecido pelo InfinityFree
$DB_NAME = "if0_40565266_sgalm_db";    // Nome da base de dados
$DB_USER = "if0_40565266";             // Username
$DB_PASS = "Chirrimecalado1";           // Substitui pela senha real

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (Exception $e) {
    die('Erro na conexão DB: ' . $e->getMessage());
}
