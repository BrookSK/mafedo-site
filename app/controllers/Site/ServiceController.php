<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Setting;

final class ServiceController extends Controller
{
    public function index(): void
    {
        $this->view('site/services', [
            'meta' => [
                'title'       => 'Serviços · ' . Setting::get('company_name', 'Mafedo Engenharia'),
                'description' => 'Conheça os serviços de engenharia oferecidos pela Mafedo.',
            ],
            'services' => (new Service())->active(),
        ]);
    }

    public function show(string $slug): void
    {
        $service = (new Service())->activeBySlug($slug);
        if ($service === null) {
            $this->notFound();
        }

        $structured = [
            '@context' => 'https://schema.org',
            '@type'    => 'Service',
            'name'     => $service['title'],
            'url'      => url('/servicos/' . $service['slug']),
            'provider' => ['@type' => 'Organization', 'name' => (string) Setting::get('company_name', 'Mafedo Engenharia')],
        ];
        if (!empty($service['short_description'])) {
            $structured['description'] = (string) $service['short_description'];
        }

        $this->view('site/service-single', [
            'meta' => [
                'title'       => ($service['seo_title'] ?: $service['title']) . ' · Mafedo Engenharia',
                'description' => $service['seo_description'] ?: str_excerpt((string) $service['short_description'], 160),
                'og_type'     => 'article',
                'structured'  => $structured,
            ],
            'service' => $service,
            'others'  => array_slice(array_filter((new Service())->active(), static fn ($s) => $s['id'] !== $service['id']), 0, 4),
        ]);
    }

    private function notFound(): never
    {
        http_response_code(404);
        echo \App\Core\View::render('site/errors/generic', ['code' => 404, 'message' => 'Serviço não encontrado'], 'layouts/site');
        exit;
    }
}
