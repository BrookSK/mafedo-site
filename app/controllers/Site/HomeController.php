<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;

final class HomeController extends Controller
{
    public function index(): void
    {
        $companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');

        $this->view('site/home', [
            'meta' => [
                'title'       => $companyName . ' · Engenharia que transforma projetos em resultados',
                'description' => (string) Setting::get('seo_meta_description', ''),
                'structured'  => [
                    '@context' => 'https://schema.org',
                    '@type'    => 'Organization',
                    'name'     => $companyName,
                    'url'      => base_url(),
                    'email'    => Setting::get('contact_email', ''),
                    'telephone' => Setting::get('contact_phone', ''),
                ],
            ],
            'hero' => [
                'eyebrow'  => (string) Setting::get('home_hero_eyebrow', $companyName),
                'title'    => (string) Setting::get('home_hero_title', 'Engenharia que transforma projetos em resultados.'),
                'subtitle' => (string) Setting::get('home_hero_subtitle', ''),
                'image'    => (string) Setting::get('home_hero_image', ''),
            ],
            'aboutText'        => (string) Setting::get('home_about_text', ''),
            'featuredServices' => (new Service())->featured(6),
            'featuredProjects' => (new Project())->featured(6),
        ]);
    }
}
