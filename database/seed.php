<?php
/**
 * Seed inicial do sistema.
 *
 * Cria:
 *  - Permissões e papéis (super-admin, editor)
 *  - Configurações padrão (settings)
 *  - Conteúdo de exemplo claramente marcado como PLACEHOLDER (serviços/projetos)
 *  - Usuário Super Admin (credenciais informadas de forma segura)
 *
 * Uso:
 *   php database/seed.php
 *     -> pergunta nome, e-mail e senha do Super Admin de forma interativa
 *
 *   php database/seed.php --email=admin@mafedo.com.br --name="Super Admin" --password=SENHA
 *     -> modo não interativo (evite deixar a senha no histórico do shell)
 *
 *   php database/seed.php --no-admin
 *     -> apenas papéis/permissões/configurações/conteúdo, sem criar admin
 *
 * NÃO há senha hardcoded: a senha é sempre fornecida no momento da instalação.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Este script só pode ser executado via linha de comando.');
}

$basePath = dirname(__DIR__);
require $basePath . '/app/core/Autoloader.php';
(new App\Core\Autoloader($basePath . '/app'))->register();

use App\Core\Config;
use App\Core\Database;

Config::load(require $basePath . '/config/config.php');
date_default_timezone_set((string) Config::get('timezone', 'America/Sao_Paulo'));

// ---- parse de argumentos ------------------------------------------------
$args = [];
foreach (array_slice($argv, 1) as $arg) {
    if (preg_match('/^--([^=]+)(?:=(.*))?$/', $arg, $m)) {
        $args[$m[1]] = $m[2] ?? true;
    }
}

$now = date('Y-m-d H:i:s');

function upsertPermission(string $name, string $desc, string $now): int
{
    $existing = Database::fetch('SELECT id FROM permissions WHERE name = :n', ['n' => $name]);
    if ($existing) {
        return (int) $existing['id'];
    }
    return (int) Database::insert(
        'INSERT INTO permissions (name, description, created_at) VALUES (:n, :d, :c)',
        ['n' => $name, 'd' => $desc, 'c' => $now]
    );
}

function upsertRole(string $name, string $desc, string $now): int
{
    $existing = Database::fetch('SELECT id FROM roles WHERE name = :n', ['n' => $name]);
    if ($existing) {
        return (int) $existing['id'];
    }
    return (int) Database::insert(
        'INSERT INTO roles (name, description, created_at) VALUES (:n, :d, :c)',
        ['n' => $name, 'd' => $desc, 'c' => $now]
    );
}

function attachPermission(int $roleId, int $permissionId): void
{
    $exists = Database::fetch(
        'SELECT 1 FROM role_permissions WHERE role_id = :r AND permission_id = :p',
        ['r' => $roleId, 'p' => $permissionId]
    );
    if (!$exists) {
        Database::run(
            'INSERT INTO role_permissions (role_id, permission_id) VALUES (:r, :p)',
            ['r' => $roleId, 'p' => $permissionId]
        );
    }
}

function upsertSetting(string $key, mixed $value, string $type, string $group, string $now): void
{
    $existing = Database::fetch('SELECT id FROM settings WHERE setting_key = :k', ['k' => $key]);
    if ($existing) {
        return; // não sobrescreve configurações já existentes
    }
    Database::run(
        'INSERT INTO settings (setting_key, setting_value, type, setting_group, created_at)
         VALUES (:k, :v, :t, :g, :c)',
        ['k' => $key, 'v' => (string) $value, 't' => $type, 'g' => $group, 'c' => $now]
    );
}

try {
    echo "== Seed Mafedo ==\n";

    // ---- Permissões -----------------------------------------------------
    $permissions = [
        'dashboard.view'   => 'Visualizar dashboard',
        'users.view'       => 'Listar usuários',
        'users.create'     => 'Criar usuários',
        'users.edit'       => 'Editar usuários',
        'users.delete'     => 'Excluir usuários',
        'services.view'    => 'Listar serviços',
        'services.create'  => 'Criar serviços',
        'services.edit'    => 'Editar serviços',
        'services.delete'  => 'Excluir serviços',
        'projects.view'    => 'Listar projetos',
        'projects.create'  => 'Criar projetos',
        'projects.edit'    => 'Editar projetos',
        'projects.delete'  => 'Excluir projetos',
        'messages.view'    => 'Ver mensagens',
        'messages.delete'  => 'Excluir mensagens',
        'settings.view'    => 'Ver configurações',
        'settings.edit'    => 'Editar configurações',
    ];

    $permIds = [];
    foreach ($permissions as $name => $desc) {
        $permIds[$name] = upsertPermission($name, $desc, $now);
    }
    echo "- Permissões: " . count($permIds) . "\n";

    // ---- Papéis ---------------------------------------------------------
    $superAdminRole = upsertRole('super-admin', 'Acesso total ao sistema', $now);
    $editorRole = upsertRole('editor', 'Gerencia conteúdo (serviços, projetos, mensagens)', $now);

    // Super Admin recebe todas as permissões
    foreach ($permIds as $pid) {
        attachPermission($superAdminRole, $pid);
    }
    // Editor: conteúdo e mensagens, sem usuários/configurações
    $editorPerms = [
        'dashboard.view',
        'services.view', 'services.create', 'services.edit', 'services.delete',
        'projects.view', 'projects.create', 'projects.edit', 'projects.delete',
        'messages.view', 'messages.delete',
    ];
    foreach ($editorPerms as $p) {
        attachPermission($editorRole, $permIds[$p]);
    }
    echo "- Papéis: super-admin, editor\n";

    // ---- Configurações padrão ------------------------------------------
    // Dados reais da Mafedo NÃO são inventados: ficam vazios para preenchimento no painel.
    $settings = [
        // Geral
        ['company_name', 'Mafedo Engenharia', 'string', 'general'],
        ['company_logo', '', 'string', 'general'],
        ['company_favicon', '', 'string', 'general'],
        ['contact_email', '', 'string', 'general'],
        ['contact_phone', '', 'string', 'general'],
        ['contact_address', '', 'text', 'general'],
        ['business_hours', '', 'text', 'general'],
        // SEO
        ['seo_site_title', 'Mafedo Engenharia', 'string', 'seo'],
        ['seo_meta_description', 'Mafedo Engenharia — soluções em engenharia com qualidade técnica, segurança e capacidade de execução.', 'text', 'seo'],
        ['seo_keywords', '', 'string', 'seo'],
        ['seo_og_image', '', 'string', 'seo'],
        ['seo_google_analytics', '', 'string', 'seo'],
        ['seo_search_console', '', 'string', 'seo'],
        // WhatsApp
        ['whatsapp_number', '', 'string', 'whatsapp'],
        ['whatsapp_message', 'Olá! Gostaria de falar sobre um projeto.', 'string', 'whatsapp'],
        ['whatsapp_enabled', '0', 'bool', 'whatsapp'],
        // Redes sociais
        ['social_instagram', 'https://www.instagram.com/mafedo_engenharia/', 'string', 'social'],
        ['social_facebook', '', 'string', 'social'],
        ['social_linkedin', '', 'string', 'social'],
        ['social_youtube', '', 'string', 'social'],
        // SMTP (senha é criptografada ao salvar pelo painel)
        ['smtp_host', '', 'string', 'smtp'],
        ['smtp_port', '587', 'string', 'smtp'],
        ['smtp_username', '', 'string', 'smtp'],
        ['smtp_password', '', 'encrypted', 'smtp'],
        ['smtp_encryption', 'tls', 'string', 'smtp'],
        ['smtp_from_name', 'Mafedo Engenharia', 'string', 'smtp'],
        ['smtp_from_email', '', 'string', 'smtp'],
        ['smtp_to_email', '', 'string', 'smtp'],
        // Home / institucional (conteúdo editável)
        ['home_hero_eyebrow', 'Mafedo Engenharia', 'string', 'home'],
        ['home_hero_title', 'Engenharia que transforma projetos em resultados.', 'string', 'home'],
        ['home_hero_subtitle', 'Planejamento, execução e gestão de obras com rigor técnico e compromisso com cada entrega.', 'text', 'home'],
        ['home_hero_image', '', 'string', 'home'],
        ['home_about_text', 'A Mafedo Engenharia atua com soluções completas em engenharia, unindo competência técnica, segurança e capacidade de execução para entregar projetos de alto padrão.', 'text', 'home'],
    ];
    foreach ($settings as [$k, $v, $t, $g]) {
        upsertSetting($k, $v, $t, $g, $now);
    }
    echo "- Configurações padrão criadas (campos reais ficam vazios para edição no painel)\n";

    // ---- Super Admin ----------------------------------------------------
    if (!isset($args['no-admin'])) {
        $name = is_string($args['name'] ?? null) ? $args['name'] : null;
        $email = is_string($args['email'] ?? null) ? $args['email'] : null;
        $password = is_string($args['password'] ?? null) ? $args['password'] : null;

        if ($name === null) {
            $name = trim((string) readline('Nome do Super Admin [Super Admin]: '));
            if ($name === '') {
                $name = 'Super Admin';
            }
        }
        while ($email === null || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email = trim((string) readline('E-mail do Super Admin: '));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "  E-mail inválido.\n";
                $email = null;
            }
        }
        while ($password === null || strlen($password) < 8) {
            $password = (string) readline('Senha (mín. 8 caracteres): ');
            if (strlen($password) < 8) {
                echo "  A senha deve ter ao menos 8 caracteres.\n";
                $password = null;
            }
        }

        $existing = Database::fetch('SELECT id FROM users WHERE email = :e', ['e' => $email]);
        if ($existing) {
            echo "- Usuário com este e-mail já existe (id {$existing['id']}). Pulando criação.\n";
            $userId = (int) $existing['id'];
        } else {
            $userId = (int) Database::insert(
                'INSERT INTO users (name, email, password, status, created_at)
                 VALUES (:n, :e, :p, 1, :c)',
                ['n' => $name, 'e' => $email, 'p' => password_hash($password, PASSWORD_DEFAULT), 'c' => $now]
            );
            echo "- Super Admin criado (id {$userId}).\n";
        }

        $hasRole = Database::fetch(
            'SELECT 1 FROM user_roles WHERE user_id = :u AND role_id = :r',
            ['u' => $userId, 'r' => $superAdminRole]
        );
        if (!$hasRole) {
            Database::run(
                'INSERT INTO user_roles (user_id, role_id) VALUES (:u, :r)',
                ['u' => $userId, 'r' => $superAdminRole]
            );
        }
        echo "- Papel super-admin atribuído.\n";
    }

    echo "\nSeed concluído com sucesso.\n";
    echo "Acesse o painel em: /admin/login\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, 'ERRO no seed: ' . $e->getMessage() . "\n");
    exit(1);
}
