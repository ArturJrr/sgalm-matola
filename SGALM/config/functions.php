<?php
// Inicia sessão se ainda não iniciado
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Verifica se o utilizador está logado
function is_logged_in() {
    return isset($_SESSION['user']);
}

// Retorna os dados do utilizador logado
function current_user() {
    return $_SESSION['user'] ?? null;
}

// Redireciona para login se não estiver logado
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Função para escapar saída HTML (para evitar XSS)
function esc($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
