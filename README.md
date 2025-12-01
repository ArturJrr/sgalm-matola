# sgalm-matola
Sistema de Gestão de Licenças Municipais - Matola
# SGALM - Matola

## Descrição do Projeto

O **SGALM (Sistema de Gestão de Licenças Municipais)** é um sistema desenvolvido para o município da Matola, com o objetivo de gerir licenças municipais de forma eficiente.  
O sistema permite:

- Login seguro para administradores e agentes
- Cadastro, aprovação e recusa de licenças
- Dashboard interativo com estatísticas e gráficos
- Consulta pública de licenças
- Gestão de usuários e permissões
- Relatórios detalhados por período

O sistema possui interface moderna, responsiva e animada, garantindo uma experiência de usuário completa.

---

## Estrutura do Projeto

public/ → Páginas públicas e privadas (login, dashboard, licenças, usuários, configurações)
config/ → Configurações e conexão com banco de dados
tools/ → Ferramentas auxiliares (ex: criação de admin)
assets/ → CSS, JS, imagens
sql/ → Scripts SQL para criação e inicialização do banco

yaml
Copiar código

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

1. Criar uma conta gratuita em um serviço de hospedagem (ex: [InfinityFree](https://infinityfree.net)).
2. Fazer upload de todos os arquivos do projeto via **FTP**.
3. Criar o banco de dados remoto e configurar `config/db.php` com as credenciais fornecidas pelo provedor.
4. Acessar o sistema pelo domínio fornecido  `(https://sgalmmatola.wuaze.com/public/index1.php#login)`.

---

## Contato Técnico

- Desenvolvedor: **Artur Júnior**
- Email: artur75chirrime@gmail.com
- Telefone/WhatsApp: +258 853245713
