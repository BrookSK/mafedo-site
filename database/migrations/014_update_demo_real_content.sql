-- =============================================================================
-- Conteúdo de exemplo REALINHADO com a atuação real da Mafedo.
--
-- Baseado no site institucional da própria empresa (mafedo.com.br):
--   "A Mafedo Construção e Reforma está capacitada para atuar nos segmentos
--    corporativo, comercial, predial e residencial, desenvolvendo atividades
--    de construções, reformas, manutenção, instalações prediais."
--
-- Esta migration substitui o conteúdo de demonstração anterior (013) por
-- serviços e projetos coerentes com os segmentos reais. Nomes de projeto são
-- descritivos (sem nomes-fantasia inventados) e devem ser substituídos pelo
-- material real das obras no painel. Imagens continuam como mockup.
--
-- NÃO edita a 013 (regra do projeto); cria uma nova migration que ajusta os dados.
-- =============================================================================

-- Remove o conteúdo demo anterior (mantém usuários/configurações intactos).
DELETE FROM project_images;
DELETE FROM projects;
DELETE FROM services;

-- ---------- Serviços (atividades reais da Mafedo) ----------
INSERT INTO services (title, slug, short_description, description, image, featured, sort_order, status, created_at) VALUES
('Construção Civil', 'construcao-civil',
 'Execução de obras nos segmentos corporativo, comercial, predial e residencial.',
 'Executamos obras de construção civil com equipe especializada, garantindo padrão de qualidade, otimização de recursos, controle de custo e cumprimento de prazo. Atuação nos segmentos corporativo, comercial, predial e residencial.',
 '/assets/images/projetos/comercial.jpg', 1, 1, 1, NOW()),
('Reformas', 'reformas',
 'Reformas corporativas, comerciais, prediais e residenciais com segurança e acabamento.',
 'Planejamento e execução de reformas de todos os portes, da adequação de espaços à requalificação completa, com organização de obra e respeito aos prazos.',
 '/assets/images/projetos/residencial.jpg', 1, 2, 1, NOW()),
('Manutenção Predial', 'manutencao-predial',
 'Manutenção preventiva e corretiva para empresas, condomínios e edifícios.',
 'Serviços de manutenção predial preventiva e corretiva, mantendo a integridade, a segurança e o funcionamento das edificações com equipe de suporte dedicada.',
 '/assets/images/projetos/industrial.jpg', 1, 3, 1, NOW()),
('Instalações Prediais', 'instalacoes-prediais',
 'Instalações elétricas, hidráulicas e complementares executadas por profissionais capacitados.',
 'Execução de instalações prediais com conformidade técnica e segurança, integradas às obras de construção e reforma.',
 '/assets/images/projetos/infraestrutura.jpg', 1, 4, 1, NOW()),
('Gerenciamento de Obras', 'gerenciamento-de-obras',
 'Coordenação de rotina com foco em qualidade, prazo e redução de custos.',
 'Gerenciamento de obras com acompanhamento contínuo, alinhamento da equipe, minimização de custos e melhoria contínua — do planejamento à entrega.',
 '/assets/images/projetos/logistica.jpg', 0, 5, 1, NOW()),
('Projetos e Regularização', 'projetos-e-regularizacao',
 'Apoio técnico, projetos e regularização para viabilizar sua obra.',
 'Suporte técnico para projetos e regularização de obras, oferecendo tranquilidade ao cliente em todas as etapas.',
 '/assets/images/projetos/retrofit.jpg', 0, 6, 1, NOW());

-- ---------- Projetos (nomes descritivos por segmento real; imagens mockup) ----------
INSERT INTO projects
(title, slug, short_description, description, main_image, category, location, year, characteristics, featured, sort_order, status, created_at) VALUES
('Reforma Corporativa', 'reforma-corporativa',
 'Reforma de espaço corporativo em São Paulo, SP.',
 'Projeto de exemplo no segmento corporativo. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/comercial.jpg', 'Corporativo', 'São Paulo, SP', '2024',
 'Segmento: Corporativo
Escopo: Reforma e adequação de espaço
Local: São Paulo, SP
Entrega dentro do prazo',
 1, 1, 1, NOW()),
('Construção Residencial', 'construcao-residencial',
 'Obra residencial em São Paulo, SP.',
 'Projeto de exemplo no segmento residencial. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/residencial.jpg', 'Residencial', 'São Paulo, SP', '2024',
 'Segmento: Residencial
Escopo: Construção
Local: São Paulo, SP
Entrega dentro do prazo',
 1, 2, 1, NOW()),
('Obra Comercial', 'obra-comercial',
 'Obra comercial em São Paulo, SP.',
 'Projeto de exemplo no segmento comercial. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/industrial.jpg', 'Comercial', 'São Paulo, SP', '2023',
 'Segmento: Comercial
Escopo: Construção e instalações
Local: São Paulo, SP
Entrega dentro do prazo',
 1, 3, 1, NOW()),
('Manutenção Predial', 'manutencao-predial-projeto',
 'Manutenção predial em edifício na Grande São Paulo.',
 'Projeto de exemplo no segmento predial. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/infraestrutura.jpg', 'Predial', 'Grande São Paulo', '2023',
 'Segmento: Predial
Escopo: Manutenção preventiva e corretiva
Local: Grande São Paulo
Equipe de suporte dedicada',
 0, 4, 1, NOW()),
('Reforma Comercial', 'reforma-comercial',
 'Reforma de loja/escritório em São Paulo, SP.',
 'Projeto de exemplo no segmento comercial. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/retrofit.jpg', 'Comercial', 'São Paulo, SP', '2022',
 'Segmento: Comercial
Escopo: Reforma
Local: São Paulo, SP
Entrega dentro do prazo',
 0, 5, 1, NOW()),
('Instalações Prediais', 'instalacoes-prediais-projeto',
 'Execução de instalações prediais em obra na Grande São Paulo.',
 'Projeto de exemplo. As imagens são ilustrativas e devem ser substituídas pelas fotos reais da obra no painel administrativo.',
 '/assets/images/projetos/logistica.jpg', 'Predial', 'Grande São Paulo', '2022',
 'Segmento: Predial
Escopo: Instalações elétricas e hidráulicas
Local: Grande São Paulo
Conformidade técnica',
 0, 6, 1, NOW());
