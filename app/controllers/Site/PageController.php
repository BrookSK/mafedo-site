<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Setting;

final class PageController extends Controller
{
    private function company(): string
    {
        return (string) Setting::get('company_name', 'Mafedo Engenharia');
    }

    public function about(): void
    {
        $this->view('site/about', [
            'meta' => [
                'title'       => c('sobre.seo_title') ?: 'A Mafedo · ' . $this->company(),
                'description' => c('sobre.seo_description'),
                'og_image'    => upload_url(c('sobre.intro_image')),
            ],
            'services' => (new Service())->active(),
        ]);
    }

    public function privacy(): void
    {
        $this->view('site/privacy', [
            'meta' => [
                'title'       => c('legal.privacy_title') . ' · ' . $this->company(),
                'description' => c('legal.privacy_title') . ' da ' . $this->company() . '.',
            ],
        ]);
    }

    public function terms(): void
    {
        $this->view('site/terms', [
            'meta' => [
                'title'       => c('legal.terms_title') . ' · ' . $this->company(),
                'description' => c('legal.terms_title') . ' do site da ' . $this->company() . '.',
            ],
        ]);
    }
}
