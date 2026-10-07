-- =============================================================================
-- Ajuste de textos institucionais e SEO com base na atuação real da Mafedo
-- (segmentos corporativo, comercial, predial e residencial; construção,
-- reforma, manutenção e instalações prediais).
--
-- Usa UPDATE (as chaves já existem desde a 012). Não edita migrations anteriores.
-- =============================================================================

UPDATE settings SET setting_value = 'Mafedo Engenharia — Construção, Reforma e Manutenção em São Paulo'
  WHERE setting_key = 'seo_site_title';

UPDATE settings SET setting_value = 'Mafedo Engenharia: construção civil, reformas, manutenção e instalações prediais nos segmentos corporativo, comercial, predial e residencial em São Paulo. Qualidade, prazo e confiança.'
  WHERE setting_key = 'seo_meta_description';

UPDATE settings SET setting_value = 'construção civil, reforma, manutenção predial, instalações prediais, engenharia, São Paulo, obras corporativas, comerciais, residenciais'
  WHERE setting_key = 'seo_keywords';

UPDATE settings SET setting_value = 'Engenharia que transforma projetos em resultados.'
  WHERE setting_key = 'home_hero_title';

UPDATE settings SET setting_value = 'Construção, reforma, manutenção e instalações prediais com qualidade técnica, prazo e confiança — nos segmentos corporativo, comercial, predial e residencial.'
  WHERE setting_key = 'home_hero_subtitle';

UPDATE settings SET setting_value = 'A Mafedo está capacitada para atuar nos segmentos corporativo, comercial, predial e residencial, desenvolvendo construções, reformas, manutenção e instalações prediais. Unimos equipe especializada, gerenciamento eficiente e compromisso com o prazo para entregar com qualidade e tranquilidade ao cliente.'
  WHERE setting_key = 'home_about_text';
