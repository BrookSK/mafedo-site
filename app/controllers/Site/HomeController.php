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
                'title'       => c('home.seo_title') ?: $companyName,
                'description' => c('home.seo_description'),
                'og_image'    => upload_url(c('home.hero_image')),
                'hero_header' => true,
                'structured'  => [
                    '@context' => 'https://schema.org',
                    '@type'    => 'Organization',
                    'name'     => $companyName,
                    'url'      => base_url(),
                    'email'    => Setting::get('contact_email', ''),
                    'telephone' => Setting::get('contact_phone', ''),
                ],
            ],
            'featuredServices' => (new Service())->featured(6),
            'featuredProjects' => (new Project())->featured(6),
        ]);
    }
}
