-- Configurações gerais do site (chave/valor), gerenciadas pelo painel.
-- 'type' informa como interpretar o valor (string, bool, int, text, json, encrypted).
-- 'group' agrupa por categoria (general, smtp, seo, whatsapp, social).
CREATE TABLE settings (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL,
    setting_value LONGTEXT NULL,
    type VARCHAR(20) NOT NULL DEFAULT 'string',
    setting_group VARCHAR(50) NOT NULL DEFAULT 'general',
    created_at DATETIME NOT NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
