# Mafedo Engenharia — Site Institucional + Painel Administrativo

Site institucional completo e sistema de gerenciamento de conteúdo (CMS) próprio,
desenvolvido em **PHP puro com arquitetura MVC**, sem frameworks pesados, sem
WordPress e **sem arquivo `.env`**.

O site valoriza o portfólio de projetos da Mafedo e permite que a equipe administre
conteúdos (projetos, serviços, configurações, SEO, contatos) sem alterar código.

---

## Sumário

- [Requisitos](#requisitos)
- [Estrutura do projeto](#estrutura-do-projeto)
- [Instalação](#instalação)
- [Configuração (sem .env)](#configuração-sem-env)
- [Banco de dados e migrations](#banco-de-dados-e-migrations)
- [Primeiro acesso / Super Admin](#primeiro-acesso--super-admin)
- [Área administrativa](#área-administrativa)
- [Permissões e perfis](#permissões-e-perfis)
- [SMTP e formulário de contato](#smtp-e-formulário-de-contato)
- [Uploads](#uploads)
- [SEO](#seo)
- [Deploy em produção (Apache + MySQL)](#deploy-em-produção-apache--mysql)
- [Segurança](#segurança)
- [Regras importantes](#regras-importantes)

---

## Requisitos

- **PHP 8.1+** (desenvolvido e testado em 8.5) com extensões: `pdo`, `pdo_mysql`
  (produção) ou `pdo_sqlite` (desenvolvimento), `openssl`, `mbstring`, `fileinfo`, `gd` (opcional, recomendável para imagens).
- **MySQL 5.7+ / MariaDB 10.3+** em produção.
- **Apache** com `mod_rewrite` (ou Nginx com reescrita equivalente).
- Não requer Composer nem Node.js. O frontend é PHP + HTML + CSS + JS puro.

---

## Estrutura do projeto

```
/app
    /controllers      Controllers (Site\ e Admin\)
    /core             Núcleo do MVC (Router, Kernel, Database, Auth, View, ...)
    /helpers          Funções globais (e(), url(), asset(), csrf_field(), ...)
    /middleware       AuthMiddleware, GuestMiddleware, PermissionMiddleware
    /models           Models (BaseModel + User, Role, Service, Project, ...)
    /services         UploadService, Mailer (SMTP), LoginThrottle
    /views            Templates PHP (site/, admin/, layouts/, partials/)
/config
    config.php              Configuração base (padrões de desenvolvimento)
    config.local.example.php Exemplo de config local (copiar p/ config.local.php)
/database
    /migrations       Arquivos .sql numerados (001_, 002_, ...)
    migrate.php       Executor de migrations (CLI)
    seed.php          Seed inicial (permissões, papéis, settings, Super Admin)
/public
    index.php         Front controller (DocumentRoot ideal aponta aqui)
    .htaccess         Reescrita + cache + segurança
    /assets           css/, js/, images/, favicon.svg
    /uploads          Imagens enviadas pelo painel (não versionadas)
    site.webmanifest
/routes
    web.php           Definição de todas as rotas (site + admin)
/storage
    /logs /cache /sessions /database   (graváveis; conteúdo não versionado)
index.php             Delega para /public (hospedagens sem DocumentRoot ajustável)
.htaccess             Reescrita de raiz + bloqueio de pastas sensíveis
.gitignore
README.md
```

---

## Instalação

Resumo do processo:

```
1. Configurar o banco de dados (criar database + credenciais)
2. Criar config/config.local.php a partir do exemplo
3. Executar as migrations
4. Criar o usuário Super Admin (seed)
5. Acessar /admin e configurar SMTP, dados gerais, SEO
6. Cadastrar serviços e projetos
```

Passo a passo:

**1. Clone o projeto** no servidor e garanta que `storage/` e `public/uploads/`
sejam graváveis pelo processo do PHP/Apache:

```bash
chmod -R 775 storage public/uploads
```

**2. Crie o banco** (produção):

```sql
CREATE DATABASE mafedo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mafedo_user'@'localhost' IDENTIFIED BY 'senha-forte';
GRANT ALL PRIVILEGES ON mafedo.* TO 'mafedo_user'@'localhost';
FLUSH PRIVILEGES;
```

**3. Crie `config/config.local.php`** (ver seção abaixo).

**4. Rode as migrations e o seed** (ver seções abaixo).

---

## Configuração (sem .env)

Este projeto **não usa `.env`**. A única configuração em arquivo são as credenciais
do banco de dados e a chave da aplicação, em `config/config.local.php` (não versionado).
Todo o resto (SMTP, dados da empresa, SEO, WhatsApp, redes sociais) é gerenciado pelo
**painel administrativo** e armazenado na tabela `settings`.

Copie o exemplo e ajuste:

```bash
cp config/config.local.example.php config/config.local.php
```

```php
return [
    'env'      => 'production',          // 'development' mostra erros detalhados
    'base_url' => 'https://www.mafedo.com.br',
    'app_key'  => 'CHAVE-ALEATORIA-LONGA', // gere com: php -r "echo bin2hex(random_bytes(32));"
    'db' => [
        'driver'   => 'mysql',
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'database' => 'mafedo',
        'username' => 'mafedo_user',
        'password' => 'senha-forte',
        'charset'  => 'utf8mb4',
    ],
];
```

> **Importante:** `app_key` é usada para criptografar valores sensíveis no banco
> (ex.: senha do SMTP, via AES-256-GCM). **Defina uma chave forte e não a altere**
> depois de salvar configurações criptografadas, ou elas não poderão ser lidas.

### Desenvolvimento com SQLite (opcional)

Para rodar localmente sem MySQL, use o driver `sqlite` no `config.local.php`:

```php
'db' => [
    'driver'      => 'sqlite',
    'sqlite_path' => __DIR__ . '/../storage/database/mafedo.sqlite',
],
```

As migrations foram escritas para MySQL; o executor adapta automaticamente o SQL
para SQLite em ambiente de desenvolvimento.

---

## Banco de dados e migrations

As migrations ficam em `database/migrations/`, numeradas em ordem de execução.
São arquivos **`.sql`** — você pode aplicá-las de duas formas:

### Opção A — Importar os `.sql` direto no banco (SEM terminal) ✅ recomendado p/ hospedagem

No **phpMyAdmin / Adminer / painel da hospedagem (Plesk)**, abra o banco e importe,
na ordem numérica, cada arquivo de `database/migrations/`:

```
001_create_users.sql
002_create_roles.sql
003_create_permissions.sql
004_create_role_permissions.sql
005_create_user_roles.sql
006_create_settings.sql
007_create_services.sql
008_create_projects.sql
009_create_project_images.sql
010_create_contact_messages.sql
011_create_login_attempts.sql
012_seed_rbac_and_settings.sql   <-- cria permissões, papéis, Super Admin e configurações
013_seed_demo_content.sql        <-- (opcional) serviços e projetos de exemplo com mockups
```

> Dica: no phpMyAdmin dá para selecionar e importar vários arquivos, ou colar o
> conteúdo de cada um na aba **SQL** e executar. As migrations de seed (012/013)
> usam `INSERT IGNORE`, então podem ser reimportadas sem duplicar dados.

As migrations `012` e `013` **substituem a necessidade de rodar scripts no terminal** —
elas já criam o Super Admin, as permissões, as configurações e o conteúdo de exemplo.

### Opção B — Executar pelo runner (via terminal, opcional)

```bash
php database/migrate.php          # aplica migrations pendentes
php database/migrate.php status   # lista aplicadas x pendentes
```

> Em desenvolvimento com SQLite onde `pdo_sqlite` não esteja ativo no `php.ini`:
> `php -d extension=pdo_sqlite database/migrate.php`

### ⚠️ REGRA ABSOLUTA DE MIGRATIONS

**Nunca edite uma migration que já foi criada/executada.**

Para qualquer alteração no banco (adicionar coluna, índice, tabela), crie **sempre
uma nova migration** com o próximo número:

```
database/migrations/
    001_create_users.sql
    002_create_roles.sql
    ...
    011_create_login_attempts.sql
    012_add_nova_coluna.sql   <-- nova alteração aqui, nunca editando as anteriores
```

Cada migration aplicada é registrada na tabela de controle `migrations` e nunca é
reexecutada.

---

## Primeiro acesso / Super Admin

Ao importar a migration **`012_seed_rbac_and_settings.sql`**, o sistema já cria as
permissões, os papéis (`super-admin` e `editor`), as configurações padrão e o
usuário **Super Admin**:

```
URL:    /admin
E-mail: admin@mafedo.com.br
Senha:  Mafedo@2026
```

> ⚠️ **Troque a senha no primeiro acesso** (painel → Usuários → editar). A senha
> inicial é apenas para o primeiro login e está documentada aqui de propósito.

### Conteúdo de exemplo (mockups)

A migration **`013_seed_demo_content.sql`** (opcional) popula 6 serviços e 6
projetos com imagens de demonstração (em `public/assets/images/projetos`). Importe-a
se quiser ver o site preenchido; depois edite/substitua pelo conteúdo oficial no painel.

### Alternativa via terminal (opcional)

Se preferir, os mesmos seeds existem como scripts PHP:

```bash
php database/seed.php            # interativo: pergunta nome, e-mail e senha do admin
php database/seed_demo.php       # conteúdo de exemplo
```

> As configurações deixam os **dados reais da Mafedo em branco** (telefone, e-mail,
> endereço, números institucionais). Preencha-os pelo painel — o sistema não inventa dados.

---

## Área administrativa

Disponível em **`/admin`**. Recursos:

- **Dashboard** — indicadores (projetos, serviços, mensagens não lidas, usuários).
- **Projetos** — CRUD completo, imagem principal, galeria com múltiplas imagens,
  destaque, publicar/despublicar, ordenação.
- **Serviços** — CRUD, imagem, ícone, destaque na Home, ativar/desativar, ordenação.
- **Mensagens** — listar, visualizar, marcar lida/não lida, excluir.
- **Usuários** — CRUD, atribuição de perfis, reset de senha.
- **Configurações** — abas Geral, SMTP (com envio de teste), SEO, WhatsApp, Redes sociais.

O link para a área restrita fica **discreto no rodapé** do site ("Área Restrita").

---

## Permissões e perfis

Controle de acesso baseado em papéis (RBAC):

- **super-admin** — acesso total (todas as permissões).
- **editor** — gerencia conteúdo (serviços, projetos, mensagens), sem acesso a
  usuários e configurações críticas.

Permissões disponíveis: `dashboard.view`, `users.*`, `services.*`, `projects.*`,
`messages.view`, `messages.delete`, `settings.view`, `settings.edit`.

Novos perfis podem ser criados ampliando o seed/estrutura `roles` + `role_permissions`.
O sistema impede remover/desativar o **último Super Admin ativo**.

---

## SMTP e formulário de contato

Configure o SMTP em **Configurações → SMTP** no painel. Campos: host, porta, usuário,
senha (armazenada **criptografada**), criptografia (TLS/SSL/nenhuma), nome e e-mail do
remetente e e-mail de destino. Use **"Enviar e-mail de teste"** para validar.

Fluxo do formulário de contato (`/contato`):

```
Usuário preenche → validação (front + back) → proteções (CSRF, honeypot, rate-limit)
→ mensagem SALVA no banco → tenta envio por SMTP → resposta amigável
```

Mesmo que o SMTP falhe, **a mensagem é sempre salva** no banco e fica disponível no
painel (campo "E-mail enviado" indica o status do envio).

O cliente SMTP é implementado em PHP puro (sem PHPMailer/Composer) e suporta STARTTLS
(587) e SSL implícito (465).

---

## Uploads

Imagens enviadas pelo painel são salvas em `public/uploads/<área>/<ano>/<mês>/`.
Validações do `UploadService`:

- Tipo MIME real detectado por `finfo` (não confia na extensão enviada).
- Allowlist: JPG, PNG, WEBP, GIF, AVIF, SVG. Máximo 8 MB.
- Nome de arquivo aleatório (evita path traversal e colisões).
- SVG passa por sanitização básica (remoção de scripts/handlers).
- A pasta de uploads bloqueia execução de PHP via `.htaccess`.

---

## SEO

- URLs amigáveis com slug (serviços e projetos).
- `<title>` e meta description por página, configuráveis.
- Open Graph, Twitter Card e `<link rel="canonical">`.
- `sitemap.xml` dinâmico e `robots.txt`.
- Dados estruturados JSON-LD (Organization na Home, Service e CreativeWork nas páginas internas).
- HTML semântico, headings hierárquicos, `alt` nas imagens.
- `theme-color`, favicon e `site.webmanifest`.
- Google Analytics e verificação do Search Console configuráveis pelo painel (aba SEO).

---

## Deploy em produção (Apache + MySQL)

**Opção recomendada:** aponte o **DocumentRoot do Apache para a pasta `/public`**.
O `.htaccess` dentro de `/public` já cuida da reescrita, cache e cabeçalhos.

```apache
<VirtualHost *:80>
    ServerName www.mafedo.com.br
    DocumentRoot /var/www/mafedo/public
    <Directory /var/www/mafedo/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Hospedagem compartilhada** (sem acesso ao DocumentRoot): o `.htaccess` da **raiz**
serve os assets de `/public` e encaminha as requisições ao front controller, bloqueando
acesso direto a `/app`, `/config`, `/database`, `/storage` e `/routes`.

Checklist de deploy:

1. `config/config.local.php` com `env = production` e `app_key` forte.
2. Garantir `storage/` e `public/uploads/` graváveis.
3. `php database/migrate.php` e `php database/seed.php`.
4. Configurar HTTPS (os cookies de sessão são marcados como `Secure` sob HTTPS).
5. Preencher dados da empresa, SMTP e SEO no painel.

---

## Segurança

- Senhas com `password_hash()` / `password_verify()` (bcrypt/Argon via `PASSWORD_DEFAULT`).
- Proteção CSRF em todos os formulários (token sincronizado em sessão).
- Prepared statements (PDO) em todas as queries — proteção contra SQL Injection.
- Escape de saída com `e()` (htmlspecialchars) — proteção contra XSS.
- Sessão com cookies `HttpOnly`, `SameSite=Lax`, `Secure` sob HTTPS e regeneração de ID.
- Proteção contra brute force no login (bloqueio após tentativas falhas por e-mail/IP).
- Upload seguro (MIME real, allowlist, sem execução de PHP em uploads).
- Honeypot + rate limit no formulário de contato.
- Cabeçalhos de segurança (`X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, etc.).
- Erros detalhados apenas em `env = development`; em produção, mensagens genéricas + log.
- Credenciais sensíveis nunca expostas: senha SMTP criptografada; credenciais de banco
  fora do versionamento.

---

## Regras importantes

- **Nunca** editar uma migration já criada — sempre criar uma nova.
- **Não** usar `.env`; configurações sensíveis vão para `config.local.php` (não versionado)
  ou para a tabela `settings` (criptografadas quando sensíveis).
- Os textos institucionais iniciais são **modelos** (incluindo Política de Privacidade e
  Termos de Uso) e devem ser **revisados juridicamente** antes da publicação.
- Não inserir imagens de banco de imagens como se fossem obras reais da Mafedo; usar o
  placeholder até que o material oficial seja cadastrado.
```
