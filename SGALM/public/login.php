<?php 
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/functions.php';

if(is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Credenciais inválidas.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Sistema de Gestão de Licença Municipal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

/* ===== FUNDO ANIMADO PREMIUM ===== */
body {
    margin: 0;
    height: 100vh;
    background: linear-gradient(135deg, #020024 0%, #090979 40%, #00d4ff 100%);
    overflow: hidden;
    font-family: "Poppins", sans-serif;
}

/* Ondas luminosas animadas */
body::before {
    content: "";
    position: absolute;
    width: 140%;
    height: 140%;
    background: radial-gradient(circle, rgba(255,255,255,0.14) 0%, rgba(0,0,0,0) 70%);
    top: -20%;
    left: -20%;
    animation: rotateGlow 12s linear infinite;
}

@keyframes rotateGlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* ===== CARD SUPER LUXO ===== */
.login-card {
    position: relative;
    z-index: 10;
    width: 430px;
    padding: 45px 40px;
    border-radius: 22px;
    backdrop-filter: blur(18px);
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.22);
    box-shadow: 0 0 45px rgba(0,0,0,0.55);
    animation: slideDown 1.2s ease;
    margin: auto;
    margin-top: 65px;
}

/* Glow inferior */
.login-card::after {
    content: "";
    position: absolute;
    bottom: -20px;
    left: 50%;
    width: 60%;
    height: 30px;
    transform: translateX(-50%);
    background: rgba(0,255,255,0.35);
    filter: blur(25px);
    border-radius: 50%;
}

/* ===== LOGO ANIMADA ===== */
.logo-zone {
    text-align: center;
    margin-bottom: 10px;
}

.logo-round {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #44caff, #2575fc);
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: auto;
    font-size: 40px;
    animation: popIn 1.3s ease, floating 3s ease-in-out infinite;
}

/* ===== DESCRIÇÃO DO SISTEMA ===== */
.system-info {
    text-align: center;
    color: #e8f6ff;
    margin-bottom: 25px;
    font-size: 0.9rem;
    opacity: 0.92;
    line-height: 1.4;
}

.system-info strong {
    color: #ffffff;
}

/* ===== INPUTS ESTILO NEON ===== */
.label-text {
    color: #cfe7ff;
    font-weight: 500;
}

.input-group-text {
    background: rgba(255,255,255,0.20);
    border: none;
    color: #e2e2e2;
    backdrop-filter: blur(8px);
}

.form-control {
    background: rgba(255,255,255,0.12);
    border: none;
    color: #fff;
    height: 52px;
}

.form-control:focus {
    background: rgba(255,255,255,0.21);
    color: white;
    outline: none;
    box-shadow: 0 0 15px #00d4ff;
    border-left: 3px solid #00d4ff;
    transition: .25s;
}

/* ===== BOTÃO ANIMADO ===== */
.btn-login {
    background: linear-gradient(135deg, #00d4ff, #256bfc);
    color: #fff;
    border: none;
    padding: 14px;
    font-size: 19px;
    border-radius: 12px;
    margin-top: 18px;
    transition: 0.35s;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.btn-login:hover {
    transform: translateY(-4px) scale(1.04);
    box-shadow: 0 8px 35px rgba(0,180,255,0.55);
}

.small-text {
    color: #e0e0e0;
    text-align: center;
    margin-top: 15px;
    font-size: 0.85rem;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-35px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ===== PARTÍCULAS ===== */
canvas.particles {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

</style>
</head>

<body>

<!-- Canvas Partículas -->
<canvas class="particles" id="particles"></canvas>

<div class="login-card">

    <div class="logo-zone">
        <div class="logo-round">
            <i class="fa-solid fa-city"></i>
        </div>
    </div>

    <h3 class="text-center text-white mb-3">SGALM - Matola</h3>

    <!-- ===== DESCRIÇÃO DO SISTEMA ADICIONADA AQUI ===== -->
    <div class="system-info">
        <strong>Sistema de Gestão de Licenças e Autorizações Municipais</strong><br>
        Plataforma oficial da Autarquia Municipal da Matola para emissão, análise,
        gestão e acompanhamento de licenças e atividades económicas.<br>
        Acesse com as suas credenciais para continuar.
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger text-center p-2"><?= esc($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        
        <div class="mb-3">
            <label class="label-text">Email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" placeholder="email@dominio.com" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="label-text">Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="••••••••" required>
            </div>
        </div>

        <button class="btn btn-login w-100">Entrar</button>

    </form>

    <span class="small-text">Use tools/create_admin.php para criar um administrador.</span>

</div>

<!-- SCRIPT PARTÍCULAS -->
<script>
const canvas = document.getElementById("particles");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let particles = [];

class Particle {
    constructor(){
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.size = Math.random() * 3 + 1;
        this.speedY = Math.random() * 0.7 + 0.2;
        this.speedX = Math.random() * 0.4 - 0.2;
    }
    update(){
        this.y += this.speedY;
        this.x += this.speedX;
        if(this.y > canvas.height) this.y = 0;
        if(this.x > canvas.width) this.x = 0;
        if(this.x < 0) this.x = canvas.width;
    }
    draw(){
        ctx.fillStyle = "rgba(255,255,255,0.60)";
        ctx.beginPath();
        ctx.arc(this.x,this.y,this.size,0,Math.PI * 2);
        ctx.fill();
    }
}

function init(){
    particles = [];
    for(let i=0; i < 140; i++){
        particles.push(new Particle());
    }
}

function animate(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particles.forEach(p => { p.update(); p.draw(); });
    requestAnimationFrame(animate);
}

init();
animate();
</script>

</body>
</html>
