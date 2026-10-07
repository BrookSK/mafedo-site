<?php
/**
 * Seed de CONTEÚDO DE DEMONSTRAÇÃO (opcional).
 *
 * Popula serviços e projetos de exemplo apontando para imagens de mockup em
 * public/assets/images/projetos (fotos livres do Unsplash, usadas apenas como
 * PLACEHOLDER até o material oficial da Mafedo ser cadastrado pelo painel).
 *
 * Uso:
 *   php database/seed_demo.php           # insere o conteúdo demo (idempotente)
 *   php database/seed_demo.php --reset   # limpa serviços/projetos antes de inserir
 *
 * NÃO é executado automaticamente. As imagens são claramente de demonstração.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Somente via linha de comando.');
}

$base = dirname(__DIR__);
require $base . '/app/core/Autoloader.php';
(new App\Core\Autoloader($base . '/app'))->register();

use App\Core\Config;
use App\Core\Database as DB;

Config::load(require $base . '/config/config.php');
require $base . '/app/helpers/functions.php';
date_default_timezone_set((string) Config::get('timezone', 'America/Sao_Paulo'));

$reset = in_array('--reset', $argv, true);
$now = date('Y-m-d H:i:s');

// Caminho relativo salvo no banco aponta para assets (não para uploads), via
// prefixo especial reconhecido pelo helper upload_url: começamos com '/assets'.
// Para simplificar, usamos URLs absolutas de assets gravadas no campo image.
function assetImg(string $file): string
{
    return '/assets/images/projetos/' . $file;
}

try {
    if ($reset) {
        DB::run('DELETE FROM project_images');
        DB::run('DELETE FROM projects');
        DB::run('DELETE FROM services');
        echo "Conteúdo anterior removido.\n";
    }

    // ---- Serviços ----
    $services = [
        ['Projetos Estruturais', 'Cálculo e dimensionamento de estruturas em concreto, aço e madeira, com rigor normativo.', 1, 1],
        ['Gestão e Execução de Obras', 'Planejamento, coordenação e acompanhamento de obras do início à entrega.', 1, 2],
        ['Consultoria em Engenharia', 'Apoio técnico, laudos e pareceres para decisões seguras em cada etapa.', 1, 3],
        ['Projetos Complementares', 'Hidráulica, elétrica, prevenção e demais disciplinas integradas ao projeto.', 1, 4],
        ['Reformas e Retrofit', 'Modernização e requalificação de edificações existentes com segurança.', 0, 5],
        ['Regularização e Documentação', 'Aprovação de projetos e regularização junto aos órgãos competentes.', 0, 6],
    ];
    foreach ($services as [$title, $desc, $featured, $order]) {
        $slug = slugify($title);
        if (!DB::fetch('SELECT id FROM services WHERE slug = :s', ['s' => $slug])) {
            DB::run(
                'INSERT INTO services (title, slug, short_description, description, featured, sort_order, status, created_at)
                 VALUES (:t,:s,:sd,:d,:f,:o,1,:c)',
                ['t' => $title, 's' => $slug, 'sd' => $desc,
                 'd' => $desc . "\n\n[Conteúdo de demonstração — edite ou substitua pelo texto oficial no painel.]",
                 'f' => $featured, 'o' => $order, 'c' => $now]
            );
        }
    }

    // ---- Projetos (com imagem de mockup) ----
    $projects = [
        ['Edifício Residencial Horizonte', 'Residencial', 'São Paulo, SP', '2025', 'residencial.jpg', 1, 1],
        ['Complexo Industrial Vértice', 'Industrial', 'Campinas, SP', '2024', 'industrial.jpg', 1, 2],
        ['Centro Logístico Meridiano', 'Logística', 'Guarulhos, SP', '2024', 'logistica.jpg', 1, 3],
        ['Torre Corporativa Átrio', 'Comercial', 'São Paulo, SP', '2023', 'comercial.jpg', 1, 4],
        ['Requalificação Viária Lumen', 'Infraestrutura', 'Santo André, SP', '2023', 'infraestrutura.jpg', 0, 5],
        ['Retrofit Edifício Marco', 'Retrofit', 'São Paulo, SP', '2022', 'retrofit.jpg', 0, 6],
    ];
    foreach ($projects as [$title, $cat, $loc, $year, $img, $featured, $order]) {
        $slug = slugify($title);
        if (DB::fetch('SELECT id FROM projects WHERE slug = :s', ['s' => $slug])) {
            continue;
        }
        $desc = "Projeto de demonstração na categoria {$cat}, em {$loc}. "
            . "As imagens são placeholders livres e devem ser substituídas pelo material oficial da Mafedo.";
        $chars = "Categoria: {$cat}\nLocalização: {$loc}\nAno de conclusão: {$year}\nEntrega dentro do prazo";
        DB::run(
            'INSERT INTO projects (title, slug, short_description, description, main_image, category, location, year, characteristics, featured, sort_order, status, created_at)
             VALUES (:t,:s,:sd,:d,:img,:cat,:loc,:y,:ch,:f,:o,1,:c)',
            ['t' => $title, 's' => $slug, 'sd' => "Projeto {$cat} em {$loc} ({$year}).",
             'd' => $desc, 'img' => assetImg($img), 'cat' => $cat, 'loc' => $loc, 'y' => $year,
             'ch' => $chars, 'f' => $featured, 'o' => $order, 'c' => $now]
        );
    }

    echo 'Demo aplicada. Serviços: ' . DB::scalar('SELECT COUNT(*) FROM services')
        . ' | Projetos: ' . DB::scalar('SELECT COUNT(*) FROM projects') . "\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, 'ERRO: ' . $e->getMessage() . "\n");
    exit(1);
}
