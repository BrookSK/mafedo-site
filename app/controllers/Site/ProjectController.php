<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Setting;

final class ProjectController extends Controller
{
    public function index(): void
    {
        $model = new Project();
        $category = $this->request->str('categoria') ?: null;

        $this->view('site/projects', [
            'meta' => [
                'title'       => 'Projetos · ' . Setting::get('company_name', 'Mafedo Engenharia'),
                'description' => 'Portfólio de projetos realizados pela Mafedo Engenharia.',
            ],
            'projects'   => $model->active($category),
            'categories' => $model->categories(),
            'current'    => $category,
        ]);
    }

    public function show(string $slug): void
    {
        $model = new Project();
        $project = $model->activeBySlug($slug);
        if ($project === null) {
            http_response_code(404);
            echo \App\Core\View::render('site/errors/generic', ['code' => 404, 'message' => 'Projeto não encontrado'], 'layouts/site');
            exit;
        }

        $images = (new ProjectImage())->forProject((int) $project['id']);

        // Características: uma por linha.
        $characteristics = array_values(array_filter(array_map(
            'trim',
            preg_split('/\r?\n/', (string) $project['characteristics']) ?: []
        )));

        $structured = [
            '@context' => 'https://schema.org',
            '@type'    => 'CreativeWork',
            'name'     => $project['title'],
            'url'      => url('/projetos/' . $project['slug']),
            'about'    => $project['category'] ?: 'Engenharia',
            'creator'  => ['@type' => 'Organization', 'name' => (string) Setting::get('company_name', 'Mafedo Engenharia')],
        ];
        if (!empty($project['main_image'])) {
            $structured['image'] = upload_url($project['main_image']);
        }
        if (!empty($project['short_description'])) {
            $structured['description'] = (string) $project['short_description'];
        }

        $this->view('site/project-single', [
            'meta' => [
                'title'       => ($project['seo_title'] ?: $project['title']) . ' · Mafedo Engenharia',
                'description' => $project['seo_description'] ?: str_excerpt((string) $project['short_description'], 160),
                'og_type'     => 'article',
                'og_image'    => $project['main_image'] ? upload_url($project['main_image']) : '',
                'hero_header' => true,
                'structured'  => $structured,
            ],
            'project'         => $project,
            'images'          => $images,
            'characteristics' => $characteristics,
            'related'         => $model->related((int) $project['id'], $project['category'] ?? null, 3),
        ]);
    }
}
