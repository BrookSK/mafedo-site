-- =============================================================================
-- CONTEÚDO DE DEMONSTRAÇÃO (serviços + projetos com imagens de mockup).
--
-- Importe no MySQL/MariaDB para popular o site com exemplos. As imagens vêm de
-- public/assets/images/projetos (fotos livres, usadas apenas como PLACEHOLDER
-- até o material oficial da Mafedo ser cadastrado/substituído no painel).
--
-- Idempotente (INSERT IGNORE por slug único). Pode ser removido/editado depois
-- pelo painel administrativo normalmente.
-- =============================================================================

-- ---------- Serviços ----------
INSERT IGNORE INTO services (title, slug, short_description, description, featured, sort_order, status, created_at) VALUES
('Projetos Estruturais', 'projetos-estruturais',
 'Cálculo e dimensionamento de estruturas em concreto, aço e madeira, com rigor normativo.',
 'Desenvolvemos projetos estruturais completos, do lançamento ao detalhamento executivo, garantindo segurança e eficiência. [Conteúdo de demonstração — edite no painel.]',
 1, 1, 1, NOW()),
('Gestão e Execução de Obras', 'gestao-e-execucao-de-obras',
 'Planejamento, coordenação e acompanhamento de obras do início à entrega.',
 'Coordenamos todas as frentes da obra com controle de prazo, custo e qualidade. [Conteúdo de demonstração — edite no painel.]',
 1, 2, 1, NOW()),
('Consultoria em Engenharia', 'consultoria-em-engenharia',
 'Apoio técnico, laudos e pareceres para decisões seguras em cada etapa.',
 'Oferecemos consultoria técnica especializada, laudos e pareceres de engenharia. [Conteúdo de demonstração — edite no painel.]',
 1, 3, 1, NOW()),
('Projetos Complementares', 'projetos-complementares',
 'Hidráulica, elétrica, prevenção e demais disciplinas integradas ao projeto.',
 'Integramos as disciplinas complementares ao projeto arquitetônico e estrutural. [Conteúdo de demonstração — edite no painel.]',
 1, 4, 1, NOW()),
('Reformas e Retrofit', 'reformas-e-retrofit',
 'Modernização e requalificação de edificações existentes com segurança.',
 'Requalificamos edificações existentes, modernizando estrutura e instalações. [Conteúdo de demonstração — edite no painel.]',
 0, 5, 1, NOW()),
('Regularização e Documentação', 'regularizacao-e-documentacao',
 'Aprovação de projetos e regularização junto aos órgãos competentes.',
 'Cuidamos da aprovação e regularização de projetos junto aos órgãos públicos. [Conteúdo de demonstração — edite no painel.]',
 0, 6, 1, NOW());

-- ---------- Projetos (com imagem de mockup em /assets/images/projetos) ----------
INSERT IGNORE INTO projects
(title, slug, short_description, description, main_image, category, location, year, characteristics, featured, sort_order, status, created_at) VALUES
('Edifício Residencial Horizonte', 'edificio-residencial-horizonte',
 'Projeto Residencial em São Paulo, SP (2025).',
 'Projeto de demonstração na categoria Residencial. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/residencial.jpg', 'Residencial', 'São Paulo, SP', '2025',
 'Categoria: Residencial
Localização: São Paulo, SP
Ano de conclusão: 2025
Entrega dentro do prazo',
 1, 1, 1, NOW()),
('Complexo Industrial Vértice', 'complexo-industrial-vertice',
 'Projeto Industrial em Campinas, SP (2024).',
 'Projeto de demonstração na categoria Industrial. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/industrial.jpg', 'Industrial', 'Campinas, SP', '2024',
 'Categoria: Industrial
Localização: Campinas, SP
Ano de conclusão: 2024
Entrega dentro do prazo',
 1, 2, 1, NOW()),
('Centro Logístico Meridiano', 'centro-logistico-meridiano',
 'Projeto Logística em Guarulhos, SP (2024).',
 'Projeto de demonstração na categoria Logística. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/logistica.jpg', 'Logística', 'Guarulhos, SP', '2024',
 'Categoria: Logística
Localização: Guarulhos, SP
Ano de conclusão: 2024
Entrega dentro do prazo',
 1, 3, 1, NOW()),
('Torre Corporativa Átrio', 'torre-corporativa-atrio',
 'Projeto Comercial em São Paulo, SP (2023).',
 'Projeto de demonstração na categoria Comercial. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/comercial.jpg', 'Comercial', 'São Paulo, SP', '2023',
 'Categoria: Comercial
Localização: São Paulo, SP
Ano de conclusão: 2023
Entrega dentro do prazo',
 1, 4, 1, NOW()),
('Requalificação Viária Lumen', 'requalificacao-viaria-lumen',
 'Projeto Infraestrutura em Santo André, SP (2023).',
 'Projeto de demonstração na categoria Infraestrutura. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/infraestrutura.jpg', 'Infraestrutura', 'Santo André, SP', '2023',
 'Categoria: Infraestrutura
Localização: Santo André, SP
Ano de conclusão: 2023
Entrega dentro do prazo',
 0, 5, 1, NOW()),
('Retrofit Edifício Marco', 'retrofit-edificio-marco',
 'Projeto Retrofit em São Paulo, SP (2022).',
 'Projeto de demonstração na categoria Retrofit. As imagens são placeholders e devem ser substituídas pelo material oficial da Mafedo.',
 '/assets/images/projetos/retrofit.jpg', 'Retrofit', 'São Paulo, SP', '2022',
 'Categoria: Retrofit
Localização: São Paulo, SP
Ano de conclusão: 2022
Entrega dentro do prazo',
 0, 6, 1, NOW());
