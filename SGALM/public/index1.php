<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SGALM - Portal Oficial do Município da Matola</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ===== BACKGROUND ANIMADO ===== */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #020024 0%, #090979 40%, #00d4ff 100%);
    overflow-x: hidden;
}

/* Partículas animadas */
canvas.particles {
    position: fixed;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
}

/* ===== HEADER ===== */
header {
    position: relative;
    z-index: 10;
    padding: 20px 0;
}
.navbar-brand {
    font-weight: 700;
    font-size: 1.7rem;
    color: #fff;
}
.navbar-nav .nav-link {
    color: #fff !important;
    font-weight: 500;
    transition: 0.3s;
}
.navbar-nav .nav-link:hover {
    color: #00d4ff !important;
}

/* ===== HERO ===== */
.hero {
    position: relative;
    z-index: 10;
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    text-align: center;
    padding: 0 20px;
}
.hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 15px;
    animation: fadeInUp 1s ease forwards;
}
.hero p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    animation: fadeInUp 1.2s ease forwards;
}
.hero .btn {
    font-size: 1.1rem;
    padding: 12px 30px;
    border-radius: 12px;
    transition: 0.3s;
}
.hero .btn-primary {
    background: linear-gradient(135deg, #00d4ff, #256bfc);
    border: none;
}
.hero .btn-primary:hover {
    transform: scale(1.05);
}

/* ===== SEÇÕES ===== */
.section {
    padding: 80px 20px;
    position: relative;
    z-index: 10;
}
.section h2 {
    font-size: 2.2rem;
    font-weight: 700;
    text-align: center;
    margin-bottom: 40px;
    color: #fff;
}

/* Cards informativos */
.info-cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
}
.info-card {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 18px;
    width: 260px;
    padding: 25px;
    text-align: center;
    transition: 0.4s;
    backdrop-filter: blur(12px);
}
.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 30px rgba(0,212,255,0.4);
}
.info-card i {
    font-size: 2.5rem;
    margin-bottom: 15px;
    color: #00d4ff;
}
.info-card h5 {
    font-weight: 600;
    margin-bottom: 10px;
    color: #fff;
}
.info-card p {
    font-size: 0.95rem;
    color: #ddd;
}

/* ===== FOOTER ===== */
footer {
    background: rgba(0,0,0,0.3);
    color: #fff;
    padding: 30px 20px;
    text-align: center;
}
footer a {
    color: #00d4ff;
    text-decoration: none;
}

/* ===== ANIMAÇÕES ===== */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<!-- Partículas -->
<canvas class="particles" id="particles"></canvas>

<!-- HEADER -->
<header class="container">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">SGALM Matola</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#about">Sobre</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Funcionalidades</a></li>
                <li class="nav-item"><a class="nav-link" href="#login">Login</a></li>
            </ul>
        </div>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <div>
        <h1>Bem-vindo ao SGALM - Matola</h1>
        <p>O Sistema de Gestão de Licenças do Município da Matola permite controlar, aprovar e consultar licenças municipais de forma moderna, rápida e segura.</p>
        <a href="#login" class="btn btn-primary">Acessar o Sistema</a>
    </div>
</section>

<!-- SOBRE -->
<section id="about" class="section">
    <h2>Sobre o SGALM</h2>
    <div class="container text-center">
        <p style="max-width: 800px; margin:auto; color:#ddd;">
        O SGALM é um sistema inovador criado para o Município da Matola, com o objetivo de digitalizar e otimizar o processo de emissão e gestão de licenças. 
        Permite que cidadãos, empresas e agentes municipais interajam de forma segura, com histórico completo e relatórios detalhados. 
        A plataforma é moderna, responsiva e fácil de usar, garantindo transparência e eficiência administrativa.
        </p>
    </div>
</section>

<!-- FUNCIONALIDADES -->
<section id="features" class="section">
    <h2>Funcionalidades Principais</h2>
    <div class="info-cards">
        <div class="info-card">
            <i class="fa-solid fa-file-lines"></i>
            <h5>Gerenciamento de Licenças</h5>
            <p>Submissão, aprovação e histórico completo de licenças municipais.</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-chart-line"></i>
            <h5>Relatórios Detalhados</h5>
            <p>Visualize gráficos e estatísticas sobre todas as licenças e usuários.</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-users"></i>
            <h5>Gestão de Usuários</h5>
            <p>Controle de contas de administradores e agentes do sistema.</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-lock"></i>
            <h5>Segurança</h5>
            <p>Autenticação segura e permissões controladas para todos os usuários.</p>
        </div>
        <div class="info-card">
            <i class="fa-solid fa-globe"></i>
            <h5>Consulta Pública</h5>
            <p>Permite que cidadãos consultem licenças emitidas e status sem precisar logar.</p>
        </div>
    </div>
</section>

<!-- LOGIN & CADASTRO -->
<section id="login" class="section">
    <h2>Acessar o Sistema</h2>
    <div class="container">
        <div class="row justify-content-center">
            <!-- LOGIN -->
            <div class="col-md-5">
                <div class="card p-4" style="border-radius:15px; backdrop-filter:blur(15px); background: rgba(255,255,255,0.1); color:#fff;">
                    <h4 class="text-center mb-3">Login</h4>
                    <form method="POST" action="public/login.php">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="seu@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label>Senha</label>
                            <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                        </div>
                        <button class="btn btn-primary w-100">Entrar</button>
                    </form>
                    <small class="d-block text-center mt-2">Use o tools/create_admin.php para criar um administrador.</small>
                </div>
            </div>

            <!-- CADASTRO / CONSULTA PÚBLICA -->
            <div class="col-md-5">
                <div class="card p-4" style="border-radius:15px; backdrop-filter:blur(15px); background: rgba(255,255,255,0.1); color:#fff;">
                    <h4 class="text-center mb-3">Consulta Pública</h4>
                    <p class="text-center">Visualize licenças municipais públicas sem precisar logar.</p>
                    <a href="public/public_consulta.php" class="btn btn-info w-100"><i class="fa-solid fa-eye"></i> Consultar Licenças</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© 2025 SGALM Matola - Desenvolvido por Artur Júnior. Contato: +258 853245713</p>
</footer>

<!-- PARTICULAS -->
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
        this.speedY = Math.random() * 0.5 + 0.2;
        this.speedX = Math.random() * 0.3 - 0.15;
    }
    update(){
        this.y += this.speedY;
        this.x += this.speedX;
        if(this.y > canvas.height) this.y = 0;
        if(this.x > canvas.width) this.x = 0;
        if(this.x < 0) this.x = canvas.width;
    }
    draw(){
        ctx.fillStyle = "rgba(255,255,255,0.4)";
        ctx.beginPath();
        ctx.arc(this.x,this.y,this.size,0,Math.PI*2);
        ctx.fill();
    }
}
function init(){
    particles = [];
    for(let i=0;i<120;i++){
        particles.push(new Particle());
    }
}
function animate(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particles.forEach(p=>{p.update();p.draw();});
    requestAnimationFrame(animate);
}
init();
animate();
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
