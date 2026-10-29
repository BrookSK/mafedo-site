-- =============================================================================
-- Ajustes pedidos pelo cliente (revisão de outubro/2026):
--
-- 1) WhatsApp oficial (+55 11 97953-6740) com botão flutuante ativo.
-- 2) Logo oficial no header/footer (versão horizontal clara, para fundo escuro).
-- 3) Remoção do que a Mafedo NÃO faz: manutenção predial, instalações prediais
--    e o segmento "predial". Serviços/projetos são DESATIVADOS (status = 0),
--    não apagados, para poderem ser reativados pelo painel se necessário.
-- 4) Textos institucionais e SEO realinhados (construção e reforma de alto padrão).
--
-- Não edita migrations anteriores (regra do projeto).
-- =============================================================================

-- ---------- 1) WhatsApp ----------
UPDATE settings SET setting_value = '5511979536740' WHERE setting_key = 'whatsapp_number';
UPDATE settings SET setting_value = '1' WHERE setting_key = 'whatsapp_enabled';
UPDATE settings SET setting_value = 'Olá! Vim pelo site da Mafedo e gostaria de falar sobre um projeto.'
  WHERE setting_key = 'whatsapp_message' AND (setting_value = '' OR setting_value = 'Olá! Gostaria de falar sobre um projeto.');
UPDATE settings SET setting_value = '(11) 97953-6740'
  WHERE setting_key = 'contact_phone' AND (setting_value = '' OR setting_value = '11979536740');

-- ---------- 2) Logo ----------
UPDATE settings SET setting_value = '/assets/images/brand/logo-horizontal-branco.png'
  WHERE setting_key = 'logo_header' AND setting_value = '';
UPDATE settings SET setting_value = '/assets/images/brand/logo-horizontal-branco.png'
  WHERE setting_key = 'logo_footer' AND setting_value = '';

-- ---------- 3) Serviços e projetos fora do escopo ----------
UPDATE services SET status = 0, featured = 0 WHERE slug IN ('manutencao-predial', 'instalacoes-prediais');

UPDATE services SET
  short_description = 'Obras corporativas, comerciais e residenciais executadas com rigor técnico.',
  description = 'Executamos obras de construção civil com equipe especializada, garantindo padrão de qualidade, otimização de recursos, controle de custo e cumprimento de prazo. Atuação nos segmentos corporativo, comercial e residencial.'
  WHERE slug = 'construcao-civil';

UPDATE services SET
  short_description = 'Reformas corporativas, comerciais e residenciais com acabamento de alto padrão.'
  WHERE slug = 'reformas';

UPDATE projects SET status = 0, featured = 0
  WHERE slug IN ('manutencao-predial-projeto', 'instalacoes-prediais-projeto', 'reforma-comercial');

UPDATE projects SET characteristics = 'Segmento: Comercial
Escopo: Construção
Local: São Paulo, SP
Entrega dentro do prazo'
  WHERE slug = 'obra-comercial';

-- ---------- 4) Textos e SEO ----------
UPDATE settings SET setting_value = 'Mafedo Engenharia — Construção e Reforma de Alto Padrão em São Paulo'
  WHERE setting_key = 'seo_site_title';

UPDATE settings SET setting_value = 'Mafedo Engenharia: construção civil e reformas de alto padrão nos segmentos corporativo, comercial e residencial em São Paulo. Qualidade técnica, prazo e confiança.'
  WHERE setting_key = 'seo_meta_description';

UPDATE settings SET setting_value = 'construção civil, reforma, alto padrão, engenharia, São Paulo, obras corporativas, comerciais, residenciais'
  WHERE setting_key = 'seo_keywords';

UPDATE settings SET setting_value = 'Construção e reforma de alto padrão, com qualidade técnica, prazo e confiança — nos segmentos corporativo, comercial e residencial.'
  WHERE setting_key = 'home_hero_subtitle';

UPDATE settings SET setting_value = 'A Mafedo atua nos segmentos corporativo, comercial e residencial, desenvolvendo construções e reformas de alto padrão. Unimos equipe especializada, gerenciamento eficiente e compromisso com o prazo para entregar com qualidade e tranquilidade ao cliente.'
  WHERE setting_key = 'home_about_text';
