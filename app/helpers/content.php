<?php

declare(strict_types=1);

use App\Models\Setting;

/**
 * Conteúdo editável do site (textos e imagens de todas as páginas).
 *
 * Este arquivo define, em um só lugar:
 *  - as páginas e seções que aparecem em Painel > Conteúdo do site;
 *  - os campos de cada seção (tipo, rótulo, dica);
 *  - o texto/imagem PADRÃO de cada campo.
 *
 * O que o administrador salva no painel fica na tabela `settings` com a chave
 * "content.{pagina}.{campo}". Enquanto um campo não for editado (ou se voltar
 * a ser igual ao padrão), o site usa o padrão definido aqui.
 *
 * Seções com 'repeat' viram uma lista de itens (ex.: diferenciais). Um item com
 * o primeiro campo vazio não aparece no site, então dá para "apagar" um item
 * só limpando o título no painel.
 */

if (!function_exists('content_schema')) {
    function content_schema(): array
    {
        static $schema = null;
        if ($schema !== null) {
            return $schema;
        }

        $f = static fn (string $type, string $label, string $default = '', string $hint = ''): array =>
            ['type' => $type, 'label' => $label, 'default' => $default, 'hint' => $hint];

        $img = '/assets/images/obras/';

        $schema = [
            // =================================================================
            'geral' => [
                'label' => 'Geral (todas as páginas)',
                'intro' => 'Itens que aparecem em várias páginas: menu, rodapé, números, diferenciais, missão e valores, faixa de contato e aviso de cookies. Telefone, e-mail, endereço, logos e WhatsApp ficam em Configurações.',
                'sections' => [
                    'menu' => ['label' => 'Menu do topo', 'fields' => [
                        'nav_home'     => $f('text', 'Item "Início"', 'Início'),
                        'nav_about'    => $f('text', 'Item "A Mafedo"', 'A Mafedo'),
                        'nav_services' => $f('text', 'Item "Segmentos" (página de serviços)', 'Segmentos'),
                        'nav_projects' => $f('text', 'Item "Projetos"', 'Projetos'),
                        'nav_contact'  => $f('text', 'Item "Contato"', 'Contato'),
                        'nav_cta'      => $f('text', 'Botão de destaque', 'Fale com a Mafedo'),
                    ]],
                    'numeros' => ['label' => 'Nossa trajetória em números', 'hint' => 'Aparece na página inicial e em A Mafedo.', 'fields' => [
                        'numbers_image'   => $f('image', 'Imagem ao lado dos números', $img . 'residencial-estar-02.jpg', 'De preferência uma foto vertical (em pé).'),
                        'numbers_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossa trajetória em números'),
                        'numbers_title'   => $f('text', 'Título', 'Experiência que se traduz em resultado.'),
                    ]],
                    'numeros_itens' => ['label' => 'Números (itens)', 'repeat' => 3, 'prefix' => 'number',
                        'fields' => [
                            'value' => $f('text', 'Número em destaque', '', 'Ex.: 9, +400. Deixe vazio para esconder o item.'),
                            'label' => $f('text', 'Legenda'),
                            'text'  => $f('textarea', 'Descrição'),
                        ],
                        'defaults' => [
                            ['value' => '9', 'label' => 'anos de mercado', 'text' => 'Uma trajetória de experiência, evolução e compromisso com nossos clientes.'],
                            ['value' => '+400', 'label' => 'projetos entregues', 'text' => 'Experiência consolidada em diferentes segmentos e níveis de complexidade.'],
                            ['value' => 'Excelência', 'label' => 'no atendimento', 'text' => 'Compromisso com a transparência, a qualidade e a satisfação dos nossos clientes.'],
                        ],
                    ],
                    'diferenciais' => ['label' => 'Nossos diferenciais', 'hint' => 'Na página inicial aparece o resumo; em A Mafedo aparece o texto completo.', 'fields' => [
                        'diff_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossos diferenciais'),
                        'diff_title'   => $f('text', 'Título', 'O que sustenta cada entrega.'),
                    ]],
                    'diferenciais_itens' => ['label' => 'Diferenciais (itens)', 'repeat' => 6, 'prefix' => 'diff',
                        'fields' => [
                            'title'   => $f('text', 'Título', '', 'Deixe vazio para esconder o item.'),
                            'icon'    => $f('icon', 'Ícone'),
                            'summary' => $f('textarea', 'Resumo (página inicial)'),
                            'text'    => $f('textarea', 'Texto (A Mafedo)', '', 'Prefira textos curtos (uma ou duas frases) para os blocos ficarem alinhados.'),
                        ],
                        'defaults' => [
                            ['title' => 'Equipe especializada', 'icon' => 'people',
                             'summary' => 'Profissionais qualificados e atentos a cada detalhe.',
                             'text' => 'Profissionais qualificados, com conhecimento técnico e atenção aos detalhes em todas as etapas do projeto.'],
                            ['title' => 'Gerenciamento de obras', 'icon' => 'gerenciamento',
                             'summary' => 'Planejamento, acompanhamento técnico e controle de custos.',
                             'text' => 'Gestão estruturada, com planejamento, acompanhamento técnico e controle dos custos, para mais segurança e tranquilidade.'],
                            ['title' => 'Compromisso com os prazos', 'icon' => 'clock',
                             'summary' => 'Cronogramas planejados e acompanhados de perto.',
                             'text' => 'Planejamento estratégico e acompanhamento contínuo das atividades para respeitar cada cronograma.'],
                            ['title' => 'Logística eficiente', 'icon' => 'logistica',
                             'summary' => 'Materiais e equipes no momento certo.',
                             'text' => 'Integração entre fornecedores e equipes, com agilidade no fornecimento de materiais e menos interrupções na obra.'],
                            ['title' => 'Expertise técnica', 'icon' => 'expertise',
                             'summary' => 'Das reformas pontuais às obras mais complexas.',
                             'text' => 'Experiência para executar desde reformas pontuais até intervenções de maior complexidade, com soluções personalizadas.'],
                        ],
                    ],
                    'mvv' => ['label' => 'Missão, visão e valores', 'hint' => 'Aparece na página inicial e em A Mafedo.', 'fields' => [
                        'mvv_eyebrow' => $f('text', 'Texto pequeno acima do título', 'O que nos guia'),
                        'mvv_title'   => $f('text', 'Título', 'Missão, visão e valores.'),
                        'mission'     => $f('textarea', 'Missão', 'Desenvolver soluções em engenharia, construção e reformas com qualidade, planejamento eficiente e respeito às necessidades de cada cliente.'),
                        'vision'      => $f('textarea', 'Visão', 'Ser referência em obras residenciais, corporativas e comerciais pela excelência na execução e pela confiança construída em cada entrega.'),
                        'values'      => $f('textarea', 'Valores', 'Compromisso, qualidade, confiança, transparência, responsabilidade técnica e respeito aos prazos.'),
                    ]],
                    'cta' => ['label' => 'Faixa de contato (fim das páginas)', 'fields' => [
                        'cta_eyebrow'  => $f('text', 'Texto pequeno acima do título', 'Mafedo Engenharia'),
                        'cta_title'    => $f('text', 'Título', 'Conte com a Mafedo.'),
                        'cta_text'     => $f('text', 'Subtítulo', 'Compromisso, qualidade e confiança. Fale com a nossa equipe pelo canal que preferir.'),
                        'cta_whatsapp' => $f('text', 'Botão WhatsApp', 'WhatsApp'),
                        'cta_instagram'=> $f('text', 'Botão Instagram', 'Instagram'),
                        'cta_message'  => $f('text', 'Botão mensagem', 'Enviar mensagem'),
                    ]],
                    'rodape' => ['label' => 'Rodapé', 'fields' => [
                        'footer_text'     => $f('textarea', 'Texto abaixo da logo', 'Há 9 anos desenvolvendo soluções em engenharia, construção e reformas, com compromisso, qualidade e confiança.'),
                        'footer_col1'     => $f('text', 'Título da coluna 1', 'Institucional'),
                        'footer_col2'     => $f('text', 'Título da coluna 2', 'Segmentos'),
                        'footer_col3'     => $f('text', 'Título da coluna 3', 'Contato'),
                        'footer_cta'      => $f('text', 'Link de contato', 'Fale com a Mafedo'),
                        'footer_rights'   => $f('text', 'Texto de direitos', 'Todos os direitos reservados.'),
                    ]],
                    'cookies' => ['label' => 'Aviso de cookies', 'fields' => [
                        'cookie_text'   => $f('textarea', 'Texto', 'Usamos cookies para melhorar sua experiência de navegação. Ao continuar, você concorda com nossa Política de Privacidade.', 'As palavras "Política de Privacidade" viram link automaticamente.'),
                        'cookie_accept' => $f('text', 'Botão aceitar', 'Aceitar'),
                        'cookie_reject' => $f('text', 'Botão rejeitar', 'Rejeitar'),
                    ]],
                ],
            ],

            // =================================================================
            'home' => [
                'label' => 'Página inicial',
                'url' => '/',
                'sections' => [
                    'seo' => ['label' => 'Google e redes sociais (SEO)', 'fields' => [
                        'seo_title'       => $f('text', 'Título da aba / Google', 'Mafedo Engenharia | Compromisso, qualidade e confiança'),
                        'seo_description' => $f('textarea', 'Descrição no Google', 'Há 9 anos, a Mafedo Engenharia desenvolve soluções em engenharia, construção e reformas para os segmentos residencial, corporativo e comercial. Mais de 400 projetos entregues.'),
                    ]],
                    'hero' => ['label' => 'Topo (banner principal)', 'fields' => [
                        'hero_image'     => $f('image', 'Imagem de fundo', $img . 'residencial-estar-01.jpg', 'Use uma foto horizontal, de pelo menos 1600 px de largura.'),
                        'hero_eyebrow'   => $f('text', 'Texto pequeno acima do título', 'Mafedo Engenharia'),
                        'hero_title'     => $f('text', 'Título', 'Compromisso, qualidade e confiança.'),
                        'hero_subtitle'  => $f('textarea', 'Subtítulo', 'Há 9 anos desenvolvendo soluções em engenharia, construção e reformas para os segmentos residencial, corporativo e comercial.'),
                        'hero_btn1'      => $f('text', 'Botão principal', 'Ver portfólio'),
                        'hero_btn2'      => $f('text', 'Botão WhatsApp', 'Falar no WhatsApp'),
                    ]],
                    'atalhos' => ['label' => 'Barra de atalhos (rodapé do banner)', 'hint' => 'Cada item leva direto à seção da própria página inicial. Deixe vazio para esconder.', 'fields' => [
                        'jump_about'    => $f('text', 'Atalho para "Sobre"', 'A Mafedo'),
                        'jump_services' => $f('text', 'Atalho para "Segmentos"', 'Segmentos'),
                        'jump_projects' => $f('text', 'Atalho para "Portfólio"', 'Portfólio'),
                        'jump_values'   => $f('text', 'Atalho para "Missão e valores"', 'Missão e valores'),
                        'jump_contact'  => $f('text', 'Atalho para "Contato"', 'Contato'),
                    ]],
                    'sobre' => ['label' => 'Sobre a Mafedo', 'fields' => [
                        'about_image'   => $f('image', 'Imagem', $img . 'corporativo-escritorio.jpg'),
                        'about_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Sobre a Mafedo'),
                        'about_title'   => $f('text', 'Título', 'Engenharia, construção e reformas com excelência em cada etapa.'),
                        'about_text'    => $f('textarea', 'Texto', "Com 9 anos de atuação no mercado, a Mafedo Engenharia desenvolve soluções em engenharia, construção e reformas, atendendo aos segmentos residencial, corporativo e comercial.\n\nCom mais de 400 projetos entregues, reunimos experiência técnica, equipes qualificadas e uma gestão comprometida com a excelência em cada etapa da obra.", 'Deixe uma linha em branco para separar parágrafos.'),
                        'about_btn'     => $f('text', 'Botão', 'Conheça a Mafedo'),
                    ]],
                    'segmentos' => ['label' => 'Segmentos de atuação', 'hint' => 'A lista vem de Serviços (os marcados como "Destacar na Home").', 'fields' => [
                        'services_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossos segmentos de atuação'),
                        'services_title'   => $f('text', 'Título', 'Residencial, corporativo e comercial.'),
                    ]],
                    'portfolio' => ['label' => 'Portfólio', 'hint' => 'Os projetos vêm de Projetos (os marcados como destaque).', 'fields' => [
                        'projects_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Portfólio'),
                        'projects_title'   => $f('text', 'Título', 'Projetos entregues com atenção a cada detalhe.'),
                        'projects_btn'     => $f('text', 'Botão', 'Ver todos os projetos'),
                    ]],
                ],
            ],

            // =================================================================
            'sobre' => [
                'label' => 'A Mafedo',
                'url' => '/sobre',
                'sections' => [
                    'seo' => ['label' => 'Google e redes sociais (SEO)', 'fields' => [
                        'seo_title'       => $f('text', 'Título da aba / Google', 'Sobre a Mafedo Engenharia'),
                        'seo_description' => $f('textarea', 'Descrição no Google', 'Conheça a Mafedo Engenharia: 9 anos de mercado, mais de 400 projetos entregues e atuação nos segmentos residencial, corporativo e comercial.'),
                    ]],
                    'intro' => ['label' => 'Topo da página', 'fields' => [
                        'intro_image'   => $f('image', 'Imagem', $img . 'residencial-living-02.jpg'),
                        'intro_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Sobre a Mafedo'),
                        'intro_title'   => $f('text', 'Título', 'Compromisso, qualidade e confiança.'),
                        'intro_text'    => $f('textarea', 'Texto', "Com 9 anos de atuação no mercado, a Mafedo Engenharia desenvolve soluções em engenharia, construção e reformas, atendendo aos segmentos residencial, corporativo e comercial.\n\nNossa trajetória é construída com base no compromisso com a qualidade, no planejamento eficiente e no respeito às necessidades de cada cliente.\n\nCom mais de 400 projetos entregues, reunimos experiência técnica, equipes qualificadas e uma gestão comprometida com a excelência em cada etapa da obra.", 'Deixe uma linha em branco para separar parágrafos.'),
                    ]],
                    'abordagem' => ['label' => 'Nossa abordagem', 'fields' => [
                        'approach_image'   => $f('image', 'Imagem', $img . 'obra-em-execucao.jpg'),
                        'approach_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossa abordagem'),
                        'approach_title'   => $f('text', 'Título', 'Do planejamento à entrega, com acompanhamento técnico.'),
                        'approach_text'    => $f('textarea', 'Texto', 'Aliamos conhecimento técnico, gestão eficiente e soluções personalizadas para transformar projetos em ambientes funcionais, valorizados e executados com excelência.'),
                        'approach_list'    => $f('textarea', 'Lista de destaques', "Planejamento e definição clara de responsabilidades\nAcompanhamento técnico em todas as etapas\nControle de custos e respeito aos cronogramas\nIntegração entre fornecedores e equipes", 'Um item por linha.'),
                        'approach_btn'     => $f('text', 'Botão', 'Ver projetos'),
                    ]],
                    'segmentos' => ['label' => 'Segmentos', 'hint' => 'A lista vem de Serviços (todos os ativos).', 'fields' => [
                        'segments_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossos segmentos de atuação'),
                        'segments_title'   => $f('text', 'Título', 'Três segmentos, a mesma exigência de qualidade.'),
                    ]],
                ],
            ],

            // =================================================================
            'servicos' => [
                'label' => 'Segmentos (Serviços)',
                'url' => '/servicos',
                'intro' => 'A lista de segmentos/serviços em si é editada em Serviços, no menu lateral.',
                'sections' => [
                    'seo' => ['label' => 'Google e redes sociais (SEO)', 'fields' => [
                        'seo_title'       => $f('text', 'Título da aba / Google', 'Segmentos de atuação · Mafedo Engenharia'),
                        'seo_description' => $f('textarea', 'Descrição no Google', 'Obras residenciais, corporativas e comerciais: construção, reforma e modernização de ambientes com a Mafedo Engenharia.'),
                    ]],
                    'topo' => ['label' => 'Topo da página', 'fields' => [
                        'head_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Nossos segmentos de atuação'),
                        'head_title'   => $f('text', 'Título', 'Obras residenciais, corporativas e comerciais.'),
                        'head_text'    => $f('textarea', 'Texto', 'Das reformas pontuais às intervenções de maior complexidade, com planejamento, acompanhamento técnico e respeito aos prazos.'),
                    ]],
                    'processo' => ['label' => 'Como trabalhamos', 'fields' => [
                        'steps_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Como trabalhamos'),
                        'steps_title'   => $f('text', 'Título', 'Uma gestão estruturada, do início ao fim.'),
                        'steps_text'    => $f('textarea', 'Texto', 'Cada etapa é conduzida com planejamento, organização e foco na tranquilidade do cliente.'),
                    ]],
                    'processo_itens' => ['label' => 'Etapas (itens)', 'repeat' => 4, 'prefix' => 'step',
                        'fields' => [
                            'title' => $f('text', 'Título', '', 'Deixe vazio para esconder a etapa.'),
                            'text'  => $f('textarea', 'Texto'),
                        ],
                        'defaults' => [
                            ['title' => 'Entendimento', 'text' => 'Ouvimos o cliente, visitamos o local e definimos o escopo com clareza.'],
                            ['title' => 'Planejamento', 'text' => 'Organizamos prazos, recursos, custos e responsabilidades de cada profissional.'],
                            ['title' => 'Execução', 'text' => 'Equipe qualificada em obra, logística de materiais e acompanhamento técnico contínuo.'],
                            ['title' => 'Entrega', 'text' => 'Finalizamos com qualidade nos acabamentos e respeito ao cronograma combinado.'],
                        ],
                    ],
                    'destaque' => ['label' => 'Bloco com imagem', 'fields' => [
                        'feature_image'   => $f('image', 'Imagem', $img . 'residencial-estar-01.jpg'),
                        'feature_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Expertise técnica'),
                        'feature_title'   => $f('text', 'Título', 'Ambientes funcionais, valorizados e executados com excelência.'),
                        'feature_text'    => $f('textarea', 'Texto', 'A Mafedo Engenharia reúne experiência e capacidade técnica para executar projetos residenciais, corporativos e comerciais, desde reformas pontuais até intervenções de maior complexidade.'),
                        'feature_btn'     => $f('text', 'Botão', 'Ver projetos'),
                    ]],
                ],
            ],

            // =================================================================
            'projetos' => [
                'label' => 'Projetos',
                'url' => '/projetos',
                'intro' => 'Os projetos em si (fotos, títulos, descrições) são editados em Projetos, no menu lateral.',
                'sections' => [
                    'seo' => ['label' => 'Google e redes sociais (SEO)', 'fields' => [
                        'seo_title'       => $f('text', 'Título da aba / Google', 'Projetos · Mafedo Engenharia'),
                        'seo_description' => $f('textarea', 'Descrição no Google', 'Portfólio de obras residenciais, corporativas e comerciais entregues pela Mafedo Engenharia.'),
                    ]],
                    'topo' => ['label' => 'Página de projetos', 'fields' => [
                        'head_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Portfólio'),
                        'head_title'   => $f('text', 'Título', 'Obras e projetos da Mafedo.'),
                        'head_text'    => $f('textarea', 'Texto', 'Uma seleção de obras residenciais, corporativas e comerciais entregues pela nossa equipe.'),
                        'filter_all'   => $f('text', 'Filtro "Todos"', 'Todos'),
                        'empty_text'   => $f('text', 'Mensagem quando não há projetos', 'Em breve, novos projetos serão publicados aqui.'),
                    ]],
                    'detalhe' => ['label' => 'Página de cada projeto', 'fields' => [
                        'about_title'    => $f('text', 'Título do texto', 'Sobre o projeto'),
                        'features_title' => $f('text', 'Título das características', 'Características'),
                        'gallery_eyebrow'=> $f('text', 'Texto pequeno da galeria', 'Galeria'),
                        'gallery_title'  => $f('text', 'Título da galeria', 'Imagens do projeto'),
                        'cta_title'      => $f('text', 'Chamada de contato', 'Quer um projeto como este?'),
                        'cta_btn1'       => $f('text', 'Botão de contato', 'Fale com a Mafedo'),
                        'cta_btn2'       => $f('text', 'Botão outros projetos', 'Ver outros projetos'),
                        'related_title'  => $f('text', 'Título dos relacionados', 'Projetos relacionados'),
                    ]],
                    'servico' => ['label' => 'Página de cada segmento/serviço', 'fields' => [
                        'service_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Segmento'),
                        'service_btn'     => $f('text', 'Botão', 'Solicitar orçamento'),
                        'service_others'  => $f('text', 'Título "outros"', 'Outros segmentos'),
                    ]],
                ],
            ],

            // =================================================================
            'contato' => [
                'label' => 'Contato',
                'url' => '/contato',
                'intro' => 'Telefone, e-mail, endereço e horário ficam em Configurações > Geral. WhatsApp e Instagram ficam em Configurações.',
                'sections' => [
                    'seo' => ['label' => 'Google e redes sociais (SEO)', 'fields' => [
                        'seo_title'       => $f('text', 'Título da aba / Google', 'Contato · Mafedo Engenharia'),
                        'seo_description' => $f('textarea', 'Descrição no Google', 'Fale com a Mafedo Engenharia pelo WhatsApp, Instagram, e-mail ou formulário. Solicite seu orçamento.'),
                    ]],
                    'topo' => ['label' => 'Topo da página', 'fields' => [
                        'head_eyebrow' => $f('text', 'Texto pequeno acima do título', 'Fale com a Mafedo'),
                        'head_title'   => $f('text', 'Título', 'Vamos conversar sobre o seu projeto.'),
                        'head_text'    => $f('textarea', 'Texto', 'Preencha o formulário ou use um dos nossos canais de atendimento. Retornamos o mais breve possível.'),
                    ]],
                    'canais' => ['label' => 'Cards de contato', 'fields' => [
                        'label_phone'    => $f('text', 'Rótulo do telefone', 'Telefone e WhatsApp'),
                        'label_email'    => $f('text', 'Rótulo do e-mail', 'E-mail'),
                        'label_address'  => $f('text', 'Rótulo do endereço', 'Endereço'),
                        'label_hours'    => $f('text', 'Rótulo do horário', 'Atendimento'),
                        'btn_whatsapp'   => $f('text', 'Botão WhatsApp', 'WhatsApp'),
                        'btn_instagram'  => $f('text', 'Botão Instagram', 'Instagram'),
                    ]],
                    'formulario' => ['label' => 'Formulário', 'fields' => [
                        'form_title'    => $f('text', 'Título do formulário', 'Envie uma mensagem'),
                        'field_name'    => $f('text', 'Campo nome', 'Nome'),
                        'field_email'   => $f('text', 'Campo e-mail', 'E-mail'),
                        'field_phone'   => $f('text', 'Campo WhatsApp', 'WhatsApp'),
                        'field_company' => $f('text', 'Campo empresa', 'Empresa'),
                        'field_subject' => $f('text', 'Campo assunto', 'Assunto'),
                        'field_message' => $f('text', 'Campo mensagem', 'Mensagem'),
                        'form_button'   => $f('text', 'Botão de envio', 'Enviar mensagem'),
                        'form_success'  => $f('text', 'Mensagem de sucesso', 'Mensagem enviada com sucesso! Em breve entraremos em contato.'),
                    ]],
                ],
            ],

            // =================================================================
            'legal' => [
                'label' => 'Privacidade e Termos',
                'intro' => 'Formatação simples: linha começando com "## " vira título, linha começando com "- " vira item de lista, linha em branco separa parágrafos, **texto** fica em negrito e [texto](/contato) vira link. Use {empresa} para o nome da empresa.',
                'sections' => [
                    'privacidade' => ['label' => 'Política de Privacidade', 'fields' => [
                        'privacy_title' => $f('text', 'Título', 'Política de Privacidade'),
                        'privacy_body'  => $f('rich', 'Texto', "Esta Política de Privacidade descreve como a {empresa} coleta, utiliza e protege as informações fornecidas pelos usuários ao navegar neste site.\n\n## 1. Coleta de dados\nColetamos dados que você nos fornece voluntariamente, por exemplo, ao preencher o formulário de contato (nome, e-mail, WhatsApp, empresa e mensagem). Também podemos coletar dados de navegação por meio de cookies.\n\n## 2. Finalidade do uso\n- Responder a solicitações e mensagens enviadas pelo site;\n- Enviar informações sobre serviços e projetos, quando solicitado;\n- Melhorar a experiência de navegação e o desempenho do site.\n\n## 3. Cookies\nUtilizamos cookies para melhorar a navegação. Você pode gerenciar as preferências de cookies nas configurações do seu navegador.\n\n## 4. Armazenamento e segurança\nAdotamos medidas técnicas e organizacionais para proteger os dados contra acesso não autorizado, perda ou alteração indevida.\n\n## 5. Compartilhamento\nNão comercializamos dados pessoais. O compartilhamento ocorre apenas quando necessário para a prestação dos serviços ou por exigência legal.\n\n## 6. Direitos do titular\nNos termos da legislação aplicável (LGPD), você pode solicitar acesso, correção, exclusão ou portabilidade dos seus dados, bem como revogar consentimentos.\n\n## 7. Contato\nPara exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em [contato](/contato) conosco.\n\n## 8. Atualizações\nEsta política pode ser atualizada periodicamente. Recomendamos a revisão regular desta página."),
                    ]],
                    'termos' => ['label' => 'Termos de Uso', 'fields' => [
                        'terms_title' => $f('text', 'Título', 'Termos de Uso'),
                        'terms_body'  => $f('rich', 'Texto', "Estes Termos de Uso regulam o acesso e a utilização do site da {empresa}. Ao navegar, você concorda com as condições abaixo.\n\n## 1. Uso do site\nO conteúdo deste site é disponibilizado para fins informativos. O uso deve respeitar a legislação vigente e os princípios de boa-fé.\n\n## 2. Propriedade intelectual\nTextos, imagens, marcas e demais elementos deste site pertencem à {empresa} ou a seus licenciadores e são protegidos por lei. É vedada a reprodução sem autorização.\n\n## 3. Conteúdo e disponibilidade\nEmpenhamo-nos para manter as informações atualizadas e o site disponível, mas não garantimos ausência de interrupções, erros ou imprecisões.\n\n## 4. Links de terceiros\nEste site pode conter links para sites externos. Não nos responsabilizamos pelo conteúdo ou pelas práticas de privacidade desses sites.\n\n## 5. Limitação de responsabilidade\nA {empresa} não se responsabiliza por danos decorrentes do uso ou da impossibilidade de uso deste site, salvo nas hipóteses previstas em lei.\n\n## 6. Alterações\nEstes termos podem ser modificados a qualquer momento. A versão vigente estará sempre disponível nesta página.\n\n## 7. Contato\nEm caso de dúvidas sobre estes termos, entre em [contato](/contato) conosco."),
                    ]],
                ],
            ],
        ];

        return $schema;
    }
}

if (!function_exists('content_section_fields')) {
    /**
     * Campos de uma seção já expandidos (seções 'repeat' viram prefix_1_campo...).
     * @return array<string,array> chave do campo => definição (com 'default' resolvido)
     */
    function content_section_fields(array $section): array
    {
        if (empty($section['repeat'])) {
            return $section['fields'];
        }
        $out = [];
        for ($i = 1; $i <= (int) $section['repeat']; $i++) {
            foreach ($section['fields'] as $name => $def) {
                $def['default'] = (string) ($section['defaults'][$i - 1][$name] ?? '');
                $def['item'] = $i;
                $out[$section['prefix'] . '_' . $i . '_' . $name] = $def;
            }
        }
        return $out;
    }
}

if (!function_exists('content_fields')) {
    /** Mapa plano "pagina.campo" => definição, para leitura rápida. */
    function content_fields(): array
    {
        static $flat = null;
        if ($flat !== null) {
            return $flat;
        }
        $flat = [];
        foreach (content_schema() as $page => $p) {
            foreach ($p['sections'] as $section) {
                foreach (content_section_fields($section) as $key => $def) {
                    $flat[$page . '.' . $key] = $def;
                }
            }
        }
        return $flat;
    }
}

if (!function_exists('c')) {
    /**
     * Texto/imagem editável. Ex.: c('home.hero_title').
     * Usa o valor salvo no painel ou, se não houver, o padrão do schema.
     */
    function c(string $key): string
    {
        $saved = Setting::get('content.' . $key);
        if ($saved !== null) {
            return (string) $saved;
        }
        return (string) (content_fields()[$key]['default'] ?? '');
    }
}

if (!function_exists('content_items')) {
    /**
     * Itens de uma seção repetível, ignorando os que têm o 1º campo vazio.
     * Ex.: content_items('geral', 'diferenciais_itens') => [['title'=>..,'icon'=>..], ...]
     */
    function content_items(string $page, string $sectionKey): array
    {
        $section = content_schema()[$page]['sections'][$sectionKey] ?? null;
        if ($section === null || empty($section['repeat'])) {
            return [];
        }
        $names = array_keys($section['fields']);
        $items = [];
        for ($i = 1; $i <= (int) $section['repeat']; $i++) {
            $item = [];
            foreach ($names as $name) {
                $item[$name] = c($page . '.' . $section['prefix'] . '_' . $i . '_' . $name);
            }
            if (trim($item[$names[0]]) !== '') {
                $items[] = $item;
            }
        }
        return $items;
    }
}

if (!function_exists('content_paragraphs')) {
    /** Converte texto com linhas em branco em parágrafos <p> (escapados). */
    function content_paragraphs(string $text, string $class = ''): string
    {
        $cls = $class !== '' ? ' class="' . e($class) . '"' : '';
        $out = '';
        foreach (preg_split('/\R\s*\R/', trim($text)) ?: [] as $p) {
            if (trim($p) !== '') {
                $out .= '<p' . $cls . '>' . nl2br(e(trim($p))) . '</p>';
            }
        }
        return $out;
    }
}

if (!function_exists('content_lines')) {
    /** Uma linha por item, sem linhas vazias. */
    function content_lines(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', $text) ?: []), static fn ($l) => $l !== ''));
    }
}

if (!function_exists('content_rich')) {
    /**
     * Formatação simples e segura para textos longos (privacidade/termos):
     * "## " título, "- " lista, linha em branco = parágrafo, **negrito**,
     * [texto](url) link (apenas http(s) ou caminho iniciado por "/").
     */
    function content_rich(string $text): string
    {
        $company = (string) Setting::get('company_name', 'Mafedo Engenharia');
        $inline = static function (string $s) use ($company): string {
            $s = e(str_replace('{empresa}', $company, $s));
            $s = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $s) ?? $s;
            return preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', static function ($m) {
                $href = html_entity_decode($m[2], ENT_QUOTES);
                if (str_starts_with($href, '/')) {
                    $href = url($href);
                } elseif (!preg_match('#^https?://#i', $href)) {
                    return $m[1];
                }
                return '<a href="' . e($href) . '">' . $m[1] . '</a>';
            }, $s) ?? $s;
        };

        $html = '';
        foreach (preg_split('/\R\s*\R/', trim($text)) ?: [] as $block) {
            $lines = content_lines($block);
            $list = [];
            $para = [];
            $flushPara = static function () use (&$para, &$html): void {
                if ($para) { $html .= '<p>' . implode('<br>', $para) . '</p>'; $para = []; }
            };
            $flushList = static function () use (&$list, &$html): void {
                if ($list) { $html .= '<ul>' . implode('', array_map(static fn ($li) => '<li>' . $li . '</li>', $list)) . '</ul>'; $list = []; }
            };
            foreach ($lines as $line) {
                if (str_starts_with($line, '## ')) {
                    $flushPara(); $flushList();
                    $html .= '<h2>' . $inline(substr($line, 3)) . '</h2>';
                } elseif (str_starts_with($line, '- ')) {
                    $flushPara();
                    $list[] = $inline(substr($line, 2));
                } else {
                    $flushList();
                    $para[] = $inline($line);
                }
            }
            $flushPara(); $flushList();
        }
        return $html;
    }
}

if (!function_exists('content_icon_options')) {
    /** Ícones disponíveis no seletor do painel. */
    function content_icon_options(): array
    {
        return [
            'people'        => 'Equipe',
            'gerenciamento' => 'Prancheta / gestão',
            'clock'         => 'Relógio / prazo',
            'logistica'     => 'Caminhão / logística',
            'expertise'     => 'Medalha / expertise',
            'shield'        => 'Escudo / confiança',
            'gear'          => 'Engrenagem',
            'residencial'   => 'Casa',
            'corporativo'   => 'Prédio / escritório',
            'comercial'     => 'Loja',
            'construcao'    => 'Construção',
            'reforma'       => 'Reforma',
            'projeto'       => 'Projeto / compasso',
            'check'         => 'Check',
        ];
    }
}
