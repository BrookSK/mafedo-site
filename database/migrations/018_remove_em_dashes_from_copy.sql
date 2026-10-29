-- =============================================================================
-- Remove os travessões dos textos exibidos no site (pedido do cliente).
-- Primeiro reescreve os textos conhecidos. Depois troca qualquer travessão
-- restante em textos de conteúdo por vírgula.
-- Não edita migrations anteriores (regra do projeto).
-- =============================================================================

UPDATE settings SET setting_value = 'Mafedo Engenharia | Construção e Reforma de Alto Padrão em São Paulo'
  WHERE setting_key = 'seo_site_title' AND setting_value = 'Mafedo Engenharia — Construção e Reforma de Alto Padrão em São Paulo';

UPDATE settings SET setting_value = 'Construção e reforma de alto padrão para os segmentos corporativo, comercial e residencial, com qualidade técnica, prazo e confiança.'
  WHERE setting_key = 'home_hero_subtitle';

UPDATE services SET description = 'Gerenciamento de obras com acompanhamento contínuo, alinhamento da equipe, minimização de custos e melhoria contínua, do planejamento à entrega.'
  WHERE slug = 'gerenciamento-de-obras' AND description LIKE '%melhoria contínua — do planejamento%';

-- Segurança: qualquer travessão que tenha sobrado vira vírgula.
UPDATE settings SET setting_value = REPLACE(setting_value, ' — ', ', ') WHERE setting_value LIKE '% — %';
UPDATE services SET
  title = REPLACE(title, ' — ', ', '),
  short_description = REPLACE(short_description, ' — ', ', '),
  description = REPLACE(description, ' — ', ', ');
UPDATE projects SET
  title = REPLACE(title, ' — ', ', '),
  short_description = REPLACE(short_description, ' — ', ', '),
  description = REPLACE(description, ' — ', ', '),
  characteristics = REPLACE(characteristics, ' — ', ', ');
