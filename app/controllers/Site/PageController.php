<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
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
                'title'       => 'A Mafedo · ' . $this->company(),
                'description' => 'Conheça a Mafedo Engenharia: história, propósito, valores e metodologia de trabalho.',
            ],
        ]);
    }

    public function privacy(): void
    {
        $this->view('site/privacy', [
            'meta' => [
                'title'       => 'Política de Privacidade · ' . $this->company(),
                'description' => 'Política de Privacidade da ' . $this->company() . '.',
            ],
            'updatedAt' => date('d/m/Y'),
        ]);
    }

    public function terms(): void
    {
        $this->view('site/terms', [
            'meta' => [
                'title'       => 'Termos de Uso · ' . $this->company(),
                'description' => 'Termos de Uso do site da ' . $this->company() . '.',
            ],
            'updatedAt' => date('d/m/Y'),
        ]);
    }
}
