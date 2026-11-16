-- =============================================================================
-- Conteúdo enviado pelo cliente (nov/2026).
--
-- 1) Serviços passam a ser os 3 segmentos de atuação descritos pelo cliente
--    (Obras Residenciais, Corporativas e Comerciais). Os serviços antigos são
--    DESATIVADOS (status = 0), não apagados, e podem ser reativados no painel.
-- 2) Projetos de demonstração (fotos de banco de imagens) são removidos e
--    entram os projetos reais com as fotos enviadas pelo cliente.
--    Fotos em /public/assets/images/obras/ (versionadas junto com o site).
-- 3) SEO global alinhado ao texto institucional do cliente.
--
-- Os textos das páginas não ficam aqui: eles têm padrão em
-- app/helpers/content.php e são editáveis em Painel > Textos e imagens do site.
-- Não edita migrations anteriores (regra do projeto).
-- =============================================================================

-- ---------- 1) Segmentos ----------
UPDATE services SET status = 0, featured = 0
  WHERE slug IN ('construcao-civil', 'reformas', 'manutencao-predial', 'instalacoes-prediais', 'gerenciamento-de-obras', 'projetos-e-regularizacao');

INSERT IGNORE INTO services (title, slug, short_description, description, image, icon, featured, sort_order, status, created_at) VALUES
('Obras Residenciais', 'obras-residenciais',
 'Reformas e soluções personalizadas, com atenção aos detalhes, à funcionalidade e à qualidade dos acabamentos.',
 'Reformas e soluções personalizadas, com atenção aos detalhes, à funcionalidade e à qualidade dos acabamentos.

Atuamos desde reformas pontuais até intervenções de maior complexidade, com planejamento, acompanhamento técnico e respeito ao cronograma combinado.',
 '/assets/images/obras/residencial-living-02.jpg', 'residencial', 1, 1, 1, NOW()),
('Obras Corporativas', 'obras-corporativas',
 'Execução e adequação de ambientes empresariais, priorizando eficiência, organização e cumprimento dos prazos.',
 'Execução e adequação de ambientes empresariais, priorizando eficiência, organização e cumprimento dos prazos.

Com gestão estruturada e logística planejada, reduzimos interrupções e mantemos a obra alinhada à rotina da empresa.',
 '/assets/images/obras/corporativo-escritorio.jpg', 'corporativo', 1, 2, 1, NOW()),
('Obras Comerciais', 'obras-comerciais',
 'Construção, reforma e modernização de espaços comerciais, alinhando as necessidades operacionais à identidade de cada negócio.',
 'Construção, reforma e modernização de espaços comerciais, alinhando as necessidades operacionais à identidade de cada negócio.

Planejamento, controle de custos e acompanhamento técnico em cada etapa, para que o espaço fique pronto no prazo e com a qualidade esperada.',
 '/assets/images/obras/obra-em-execucao.jpg', 'comercial', 1, 3, 1, NOW());

-- ---------- 2) Projetos ----------
DELETE FROM project_images WHERE project_id IN (SELECT id FROM projects WHERE slug IN
  ('reforma-corporativa', 'construcao-residencial', 'obra-comercial', 'manutencao-predial-projeto', 'reforma-comercial', 'instalacoes-prediais-projeto'));
DELETE FROM projects WHERE slug IN
  ('reforma-corporativa', 'construcao-residencial', 'obra-comercial', 'manutencao-predial-projeto', 'reforma-comercial', 'instalacoes-prediais-projeto');

INSERT IGNORE INTO projects
(title, slug, short_description, description, main_image, category, location, year, characteristics, featured, sort_order, status, created_at) VALUES
('Apartamento com Living Integrado', 'apartamento-living-integrado',
 'Reforma residencial com integração de ambientes, marcenaria sob medida e acabamentos em madeira.',
 'Reforma residencial com integração entre estar, jantar e cozinha. O projeto combina painéis em madeira, piso em espinha de peixe e marcenaria sob medida, com atenção aos detalhes e à qualidade dos acabamentos.',
 '/assets/images/obras/residencial-living-01.jpg', 'Residencial', '', '',
 'Segmento: Residencial
Integração de ambientes
Marcenaria sob medida
Piso em madeira espinha de peixe',
 1, 1, 1, NOW()),
('Escritório Corporativo', 'escritorio-corporativo',
 'Adequação de ambiente empresarial com estações de trabalho, sala de reunião e área de convivência.',
 'Execução de ambiente corporativo com estações de trabalho, sala de reunião envidraçada e área de convivência. Ripados em madeira, iluminação técnica e jardim vertical compõem um espaço funcional e alinhado à identidade da empresa.',
 '/assets/images/obras/corporativo-escritorio.jpg', 'Corporativo', '', '',
 'Segmento: Corporativo
Estações de trabalho e sala de reunião
Ripados em madeira e jardim vertical
Iluminação técnica',
 1, 2, 1, NOW()),
('Apartamento Contemporâneo', 'apartamento-contemporaneo',
 'Reforma residencial com marcenaria sob medida, prateleiras suspensas e piso em madeira.',
 'Reforma residencial com soluções personalizadas: marcenaria sob medida, prateleiras suspensas, revestimentos especiais e iluminação de destaque, criando ambientes acolhedores e funcionais.',
 '/assets/images/obras/residencial-estar-01.jpg', 'Residencial', '', '',
 'Segmento: Residencial
Marcenaria sob medida
Prateleiras suspensas
Iluminação de destaque',
 1, 3, 1, NOW());

INSERT INTO project_images (project_id, image, alt_text, sort_order, created_at)
SELECT id, '/assets/images/obras/residencial-living-01.jpg', 'Living integrado com piso em espinha de peixe', 1, NOW() FROM projects WHERE slug = 'apartamento-living-integrado';
INSERT INTO project_images (project_id, image, alt_text, sort_order, created_at)
SELECT id, '/assets/images/obras/residencial-living-02.jpg', 'Estar com painéis em madeira e cozinha integrada', 2, NOW() FROM projects WHERE slug = 'apartamento-living-integrado';
INSERT INTO project_images (project_id, image, alt_text, sort_order, created_at)
SELECT id, '/assets/images/obras/corporativo-escritorio.jpg', 'Escritório corporativo com ripados em madeira', 1, NOW() FROM projects WHERE slug = 'escritorio-corporativo';
INSERT INTO project_images (project_id, image, alt_text, sort_order, created_at)
SELECT id, '/assets/images/obras/residencial-estar-01.jpg', 'Canto de leitura com prateleiras suspensas', 1, NOW() FROM projects WHERE slug = 'apartamento-contemporaneo';
INSERT INTO project_images (project_id, image, alt_text, sort_order, created_at)
SELECT id, '/assets/images/obras/residencial-estar-02.jpg', 'Sala com marcenaria e iluminação de destaque', 2, NOW() FROM projects WHERE slug = 'apartamento-contemporaneo';

-- ---------- 3) SEO global ----------
UPDATE settings SET setting_value = 'Mafedo Engenharia | Compromisso, qualidade e confiança'
  WHERE setting_key = 'seo_site_title';
UPDATE settings SET setting_value = 'Há 9 anos, a Mafedo Engenharia desenvolve soluções em engenharia, construção e reformas para os segmentos residencial, corporativo e comercial. Mais de 400 projetos entregues.'
  WHERE setting_key = 'seo_meta_description';
UPDATE settings SET setting_value = 'engenharia, construção, reforma, obras residenciais, obras corporativas, obras comerciais, São Paulo, Mafedo'
  WHERE setting_key = 'seo_keywords';
