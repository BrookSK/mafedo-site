-- =============================================================================
-- SEED INICIAL (RBAC + configurações + Super Admin) — para importar direto no
-- MySQL/MariaDB (phpMyAdmin, Adminer ou painel da hospedagem), SEM terminal.
--
-- Idempotente: usa INSERT IGNORE / ON DUPLICATE KEY, pode ser reimportado.
--
-- >>> SUPER ADMIN CRIADO AQUI <<<
--   E-mail: admin@mafedo.com.br
--   Senha:  Mafedo@2026
--   ALTERE A SENHA NO PRIMEIRO ACESSO (painel > Usuários).
--   O hash abaixo é um bcrypt válido gerado com password_hash().
-- =============================================================================

-- ---------- Permissões ----------
INSERT IGNORE INTO permissions (name, description, created_at) VALUES
('dashboard.view', 'Visualizar dashboard', NOW()),
('users.view',     'Listar usuários', NOW()),
('users.create',   'Criar usuários', NOW()),
('users.edit',     'Editar usuários', NOW()),
('users.delete',   'Excluir usuários', NOW()),
('services.view',  'Listar serviços', NOW()),
('services.create','Criar serviços', NOW()),
('services.edit',  'Editar serviços', NOW()),
('services.delete','Excluir serviços', NOW()),
('projects.view',  'Listar projetos', NOW()),
('projects.create','Criar projetos', NOW()),
('projects.edit',  'Editar projetos', NOW()),
('projects.delete','Excluir projetos', NOW()),
('messages.view',  'Ver mensagens', NOW()),
('messages.delete','Excluir mensagens', NOW()),
('settings.view',  'Ver configurações', NOW()),
('settings.edit',  'Editar configurações', NOW());

-- ---------- Papéis ----------
INSERT IGNORE INTO roles (name, description, created_at) VALUES
('super-admin', 'Acesso total ao sistema', NOW()),
('editor',      'Gerencia conteúdo (serviços, projetos, mensagens)', NOW());

-- ---------- Super Admin recebe TODAS as permissões ----------
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
CROSS JOIN permissions p
WHERE r.name = 'super-admin';

-- ---------- Editor: conteúdo + mensagens (sem usuários/configurações) ----------
INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT r.id, p.id
FROM roles r
JOIN permissions p ON p.name IN (
    'dashboard.view',
    'services.view','services.create','services.edit','services.delete',
    'projects.view','projects.create','projects.edit','projects.delete',
    'messages.view','messages.delete'
)
WHERE r.name = 'editor';

-- ---------- Usuário Super Admin ----------
-- Senha: Mafedo@2026  (bcrypt). TROQUE no primeiro acesso.
INSERT IGNORE INTO users (name, email, password, status, created_at) VALUES
('Super Admin', 'admin@mafedo.com.br',
 '$2y$12$dxoTo.P5HZD/K0bFAF4TkeiCgiItJMPY0UhTq8totEqutua/gyLZi',
 1, NOW());

-- Vincula o Super Admin ao papel super-admin
INSERT IGNORE INTO user_roles (user_id, role_id)
SELECT u.id, r.id
FROM users u JOIN roles r ON r.name = 'super-admin'
WHERE u.email = 'admin@mafedo.com.br';

-- ---------- Configurações padrão ----------
-- Dados reais da Mafedo ficam EM BRANCO para preenchimento no painel.
INSERT IGNORE INTO settings (setting_key, setting_value, type, setting_group, created_at) VALUES
('company_name',          'Mafedo Engenharia', 'string', 'general', NOW()),
('company_logo',          '', 'string', 'general', NOW()),
('company_favicon',       '', 'string', 'general', NOW()),
('contact_email',         '', 'string', 'general', NOW()),
('contact_phone',         '', 'string', 'general', NOW()),
('contact_address',       '', 'text',   'general', NOW()),
('business_hours',        '', 'text',   'general', NOW()),
('seo_site_title',        'Mafedo Engenharia', 'string', 'seo', NOW()),
('seo_meta_description',  'Mafedo Engenharia — soluções em engenharia com qualidade técnica, segurança e capacidade de execução.', 'text', 'seo', NOW()),
('seo_keywords',          '', 'string', 'seo', NOW()),
('seo_og_image',          '', 'string', 'seo', NOW()),
('seo_google_analytics',  '', 'string', 'seo', NOW()),
('seo_search_console',    '', 'string', 'seo', NOW()),
('whatsapp_number',       '', 'string', 'whatsapp', NOW()),
('whatsapp_message',      'Olá! Gostaria de falar sobre um projeto.', 'string', 'whatsapp', NOW()),
('whatsapp_enabled',      '0', 'bool', 'whatsapp', NOW()),
('social_instagram',      'https://www.instagram.com/mafedo_engenharia/', 'string', 'social', NOW()),
('social_facebook',       '', 'string', 'social', NOW()),
('social_linkedin',       '', 'string', 'social', NOW()),
('social_youtube',        '', 'string', 'social', NOW()),
('smtp_host',             '', 'string', 'smtp', NOW()),
('smtp_port',             '587', 'string', 'smtp', NOW()),
('smtp_username',         '', 'string', 'smtp', NOW()),
('smtp_password',         '', 'encrypted', 'smtp', NOW()),
('smtp_encryption',       'tls', 'string', 'smtp', NOW()),
('smtp_from_name',        'Mafedo Engenharia', 'string', 'smtp', NOW()),
('smtp_from_email',       '', 'string', 'smtp', NOW()),
('smtp_to_email',         '', 'string', 'smtp', NOW()),
('home_hero_eyebrow',     'Mafedo Engenharia', 'string', 'home', NOW()),
('home_hero_title',       'Engenharia que transforma projetos em resultados.', 'string', 'home', NOW()),
('home_hero_subtitle',    'Planejamento, execução e gestão de obras com rigor técnico e compromisso com cada entrega.', 'text', 'home', NOW()),
('home_hero_image',       '', 'string', 'home', NOW()),
('home_about_text',       'A Mafedo Engenharia atua com soluções completas em engenharia, unindo competência técnica, segurança e capacidade de execução para entregar projetos de alto padrão.', 'text', 'home', NOW());
