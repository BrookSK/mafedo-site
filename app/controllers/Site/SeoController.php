<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Project;
use App\Models\Service;

final class SeoController extends Controller
{
    public function sitemap(): void
    {
        header('Content-Type: application/xml; charset=UTF-8');

        $urls = [];
        $add = static function (string $loc, string $priority = '0.6', string $freq = 'monthly') use (&$urls) {
            $urls[] = ['loc' => $loc, 'priority' => $priority, 'freq' => $freq];
        };

        $add(url('/'), '1.0', 'weekly');
        $add(url('/sobre'), '0.7');
        $add(url('/servicos'), '0.8', 'weekly');
        $add(url('/projetos'), '0.9', 'weekly');
        $add(url('/contato'), '0.6');
        $add(url('/politica-de-privacidade'), '0.3', 'yearly');
        $add(url('/termos-de-uso'), '0.3', 'yearly');

        foreach ((new Service())->active() as $s) {
            $add(url('/servicos/' . $s['slug']), '0.7');
        }
        foreach ((new Project())->active() as $p) {
            $add(url('/projetos/' . $p['slug']), '0.8');
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            echo '  <url>'
                . '<loc>' . e($u['loc']) . '</loc>'
                . '<changefreq>' . $u['freq'] . '</changefreq>'
                . '<priority>' . $u['priority'] . '</priority>'
                . '</url>' . "\n";
        }
        echo '</urlset>';
    }

    public function robots(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "User-agent: *\n";
        echo "Disallow: /admin\n";
        echo "Disallow: /uploads/\n";
        echo "\n";
        echo 'Sitemap: ' . url('/sitemap.xml') . "\n";
    }
}
