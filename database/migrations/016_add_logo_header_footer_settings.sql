-- =============================================================================
-- Novas configurações: logo do HEADER e logo do FOOTER (upload pelo painel).
-- Quando preenchidas, substituem o wordmark de texto no respectivo local.
-- Idempotente (INSERT IGNORE).
-- =============================================================================

INSERT IGNORE INTO settings (setting_key, setting_value, type, setting_group, created_at) VALUES
('logo_header', '', 'string', 'general', NOW()),
('logo_footer', '', 'string', 'general', NOW());
