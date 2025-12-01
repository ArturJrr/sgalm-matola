# SGALM - Sistema de Gestão de Licenças Municipais (Matola)

## Descrição do Projeto
O SGALM é um sistema para gestão de licenças municipais em Matola.  
Permite criar, aprovar, recusar e consultar licenças, gerir usuários e visualizar relatórios detalhados.

O projeto conta com:

- Dashboard interativo com estatísticas e gráficos.
- Gestão de licenças e usuários.
- Consulta pública de licenças (prévia).
- Interface moderna, responsiva e animada.
- Sistema de login seguro com PHP e MySQL.

## Tecnologias Utilizadas
- PHP 8.x
- MySQL
- HTML5 / CSS3 / Bootstrap 5
- JavaScript / Chart.js / Font Awesome

## Estrutura de Pastas
public/ → Páginas públicas e privadas (login, dashboard, licenças, usuários, configurações)
config/ → Configurações e conexão com banco de dados
tools/ → Ferramentas auxiliares (ex: criação de admin)
assets/ → CSS, JS, imagens
sql/ → Scripts SQL para criação e inicialização do banco


---

## Passos para Testar Localmente

1. Instalar **WampServer** ou **XAMPP**.
2. Copiar a pasta do projeto para `www/` (Wamp) ou `htdocs/` (XAMPP).
3. Abrir **phpMyAdmin** e criar um banco de dados (ex: `sgalm_matola`).
4. Importar o arquivo `sql/init.sql`.
5. Configurar o arquivo `config/db.php` com as credenciais do banco local.
6. Acessar o sistema pelo navegador em:  
   `http://localhost/sgalm-matola/public/index1.php`

---

## Passos para Hospedagem Remota

1. Criar uma conta gratuita em um serviço de hospedagem  [InfinityFree] https://infinityfree.net.
2. Fazer upload de todos os arquivos do projeto via **FTP**.
3. Criar a base de dados remoto e configurar `config/db.php` com as credenciais fornecidas pelo provedor.
4. Acessar o sistema pelo domínio fornecido sgalmmatola.wuaze.com .

---

## Contato Técnico

- Desenvolvedor: **Artur Júnior**
- Email: artur75chirrime@gmail.com
- Telefone/WhatsApp: +258 853245713
