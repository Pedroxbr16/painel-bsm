# BSM Contratos — MVC PHP + MySQL

Uma base **MVC em PHP (puro)** com **MySQL**, **/admin** protegido por login, **CRUD de contratos** e um **instalador** seguro via token. Pensado para **hospedagem Hostinger** com deploy via **Git** (webhook nativo) ou **GitHub Actions (FTP/FTPS)**.

> **Stack:** PHP 8+, MySQL, Apache (mod_rewrite), HTML/CSS vanilla.

---

## ✨ Recursos

- Roteamento simples (URLs amigáveis via `.htaccess`)
- Layout base responsivo (tema escuro) e pasta `assets/` para CSS/JS
- Login com sessão e CSRF
- Área **/admin** com CRUD de **Contratos**
- **Instalador** em `/install?token=SEU_TOKEN` (cria tabelas + usuário admin)
- Pronto para **deploy automático** (Hostinger Git ou GitHub Actions FTPS)

---

## 📁 Estrutura

```
/ (raiz do repositório → publicado em public_html)
├─ index.php
├─ .htaccess
├─ assets/
│  └─ css/style.css
├─ app/
│  ├─ Controllers/
│  │  ├─ HomeController.php
│  │  ├─ AuthController.php
│  │  ├─ AdminController.php
│  │  └─ InstallerController.php
│  ├─ Models/
│  │  ├─ User.php
│  │  └─ Contract.php
│  └─ Views/
│     ├─ layouts/main.php
│     ├─ home/index.php
│     ├─ auth/login.php
│     └─ admin/
│        ├─ index.php
│        ├─ create.php
│        └─ edit.php
└─ core/
   ├─ Router.php
   ├─ Controller.php
   ├─ Config.php
   └─ DB.php
```

---

## ✅ Pré‑requisitos

- **PHP 8.1+** (recomendado 8.2/8.3)
- **MySQL** (Hostinger: hPanel → *Bancos de Dados MySQL*)
- **Apache** com **mod_rewrite** habilitado (Hostinger já tem)
- No Hostinger, **o host do MySQL é `localhost`**

---

## 🚀 Início Rápido (Hostinger)

1. **Crie o banco e o usuário** no hPanel e anote:
   - DB_NAME, DB_USER, DB_PASS
2. **Edite `core/Config.php`:**
   ```php
   class Config {
     public const BASE_URL = '/';           // ajuste se publicar em subpasta
     public const DB_HOST  = 'localhost';
     public const DB_NAME  = 'SEU_BANCO';
     public const DB_USER  = 'SEU_USUARIO';
     public const DB_PASS  = 'SUA_SENHA';
     public const DB_CHARSET = 'utf8mb4';
     public const INSTALL_TOKEN = 'coloque-um-token-forte-aqui';
   }
   ```
   > Gere um token forte (UUID/hex): `openssl rand -hex 32` ou `uuidgen`.
3. **Conecte o repositório** no Hostinger (Web → *Git*), branch desejada, **deployment path = `public_html`** e habilite *Automatic deployment* (ou use GitHub Actions, ver abaixo).
4. Acesse **`/install?token=SEU_TOKEN`** para criar tabelas e semear **admin/admin**.
5. Entre em **`/login`** → `admin / admin` → acesse **`/admin`**.
6. **Segurança:** remova/desative o instalador (ver checklist).

---

## 🔧 Configurações úteis

- **Subpasta**: se o site ficar em `seudominio.com/minhaapp`, ajuste:
  - `Config::BASE_URL = '/minhaapp/'`
  - no `.htaccess`, `RewriteBase /minhaapp/`
- **CSS/JS/Imagens**: coloque em `assets/` e referencie nas views:
  ```php
  <link rel="stylesheet" href="<?= \Core\Config::BASE_URL ?>assets/css/style.css">
  ```

---

## 🗄️ Banco de Dados

O instalador cria:

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contracts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company VARCHAR(120) NOT NULL,
  location VARCHAR(60) NOT NULL,
  status ENUM('Ativo','Negociação','Encerrado') NOT NULL DEFAULT 'Ativo',
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Usuário inicial (seed): **admin / admin** (altere depois!).

---

## 🔐 Checklist de Segurança (obrigatório após instalar)

- [ ] Remova a rota `/install` do `index.php` **ou** mude `INSTALL_TOKEN` para algo inválido
- [ ] Troque a senha do **admin** (crie tela ou mude via SQL)
- [ ] Mantenha `app/` e `core/` no webroot? O `.htaccess` já **bloqueia** acesso direto:
  ```apache
  RewriteRule ^(app|core|vendor)/ - [F,L]
  Options -Indexes
  ```
- [ ] Desative `display_errors` em produção; use logs

---

## 📦 Deploy

### Opção A — **Hostinger Git (Webhook nativo)**
1. hPanel → **Git** → *Create repository* (cole a URL do seu repo/branch)
2. **Deployment path**: `public_html`
3. Habilite **Automatic deployment** e crie o *Webhook* no GitHub (evento *push*)
4. Faça o primeiro **Deploy** no hPanel

> Observação: esse método **não roda Composer**. Se precisar “buildar”, use a Opção B.

### Opção B — **GitHub Actions (FTPS)**

1. Crie uma **conta FTP** no hPanel (Arquivos → Contas FTP) com diretório `public_html`
2. No GitHub **Secrets** do repo:
   - `FTP_SERVER` = `ftp.seu-dominio.com` (ou IP)
   - `FTP_USERNAME` = usuário FTP
   - `FTP_PASSWORD` = senha FTP
3. Workflow `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Hostinger

on:
  push:
    branches: [ "main" ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      # (se precisar) composer/npm build aqui…

      - name: Deploy via FTP
        uses: SamKirkland/FTP-Deploy-Action@v4.3.6
        with:
          server: ${{ secrets.FTP_SERVER }}
          username: ${{ secrets.FTP_USERNAME }}
          password: ${{ secrets.FTP_PASSWORD }}
          protocol: ftps
          local-dir: ./
          server-dir: public_html/
```

> Para projetos só PHP (sem build), subir tudo do repo para `public_html/` já funciona.

---

## 🧭 Rotas principais

- `GET  /` → Dashboard (cards + últimos contratos)
- `GET  /login` → Formulário de login
- `POST /login` → Autenticação
- `POST /logout` → Logout (CSRF)
- `GET  /admin` → Painel admin (protegido)
- `GET  /admin/contratos/criar` → Form criar
- `POST /admin/contratos/criar` → Salvar
- `GET  /admin/contratos/{id}/editar` → Form editar
- `POST /admin/contratos/{id}/editar` → Atualizar
- `POST /admin/contratos/{id}/excluir` → Excluir

---

## 🛠️ Solução de Problemas

- **404 nas rotas (/admin, /login):** verifique `.htaccess` e `RewriteBase`
- **500 / branco:** confira versão do PHP e `Config.php`
- **Erro de conexão MySQL:** use host **`localhost`** (para apps no mesmo plano), credenciais corretas
- **`Forbidden` no instalador:** token inválido, confira `INSTALL_TOKEN` e a URL
- **CSS não carrega:** confira caminho `assets/css/style.css` e `BASE_URL`

---

## 📄 Licença

Coloque aqui a licença do seu projeto (ex.: MIT).

---

## 🙌 Créditos

Estrutura e código base preparados para rodar em hospedagem compartilhada (Hostinger) com deploy por Git/Actions.
