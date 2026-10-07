<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Setting;
use App\Services\Mailer;

final class SettingController extends Controller
{
    public function index(): void
    {
        $this->adminView('admin/settings/index', [
            'title'    => 'Configurações',
            'general'  => Setting::group('general'),
            'smtp'     => Setting::group('smtp'),
            'seo'      => Setting::group('seo'),
            'whatsapp' => Setting::group('whatsapp'),
            'social'   => Setting::group('social'),
            'home'     => Setting::group('home'),
            'tab'      => $this->request->str('tab', 'general'),
        ]);
    }

    public function saveGeneral(): void
    {
        $this->verifyCsrf();
        Setting::setMany([
            'company_name'    => $this->request->str('company_name'),
            'contact_email'   => $this->request->str('contact_email'),
            'contact_phone'   => $this->request->str('contact_phone'),
            'contact_address' => (string) $this->request->input('contact_address', ''),
            'business_hours'  => (string) $this->request->input('business_hours', ''),
        ]);
        $this->done('general');
    }

    public function saveSeo(): void
    {
        $this->verifyCsrf();
        Setting::setMany([
            'seo_site_title'        => $this->request->str('seo_site_title'),
            'seo_meta_description'  => (string) $this->request->input('seo_meta_description', ''),
            'seo_keywords'          => $this->request->str('seo_keywords'),
            'seo_og_image'          => $this->request->str('seo_og_image'),
            'seo_google_analytics'  => $this->request->str('seo_google_analytics'),
            'seo_search_console'    => $this->request->str('seo_search_console'),
        ]);
        $this->done('seo');
    }

    public function saveWhatsapp(): void
    {
        $this->verifyCsrf();
        Setting::setMany([
            'whatsapp_number'  => preg_replace('/\D+/', '', (string) $this->request->input('whatsapp_number', '')),
            'whatsapp_message' => $this->request->str('whatsapp_message'),
            'whatsapp_enabled' => $this->request->input('whatsapp_enabled') ? '1' : '0',
        ]);
        $this->done('whatsapp');
    }

    public function saveSocial(): void
    {
        $this->verifyCsrf();
        $fields = ['social_instagram', 'social_facebook', 'social_linkedin', 'social_youtube'];
        $data = [];
        foreach ($fields as $f) {
            $value = $this->request->str($f);
            if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
                Session::flash('error', 'Informe URLs válidas nas redes sociais.');
                $this->redirect('admin/configuracoes?tab=social');
            }
            $data[$f] = $value;
        }
        Setting::setMany($data);
        $this->done('social');
    }

    public function saveSmtp(): void
    {
        $this->verifyCsrf();

        $data = [
            'smtp_host'       => $this->request->str('smtp_host'),
            'smtp_port'       => $this->request->str('smtp_port'),
            'smtp_username'   => $this->request->str('smtp_username'),
            'smtp_encryption' => $this->request->str('smtp_encryption'),
            'smtp_from_name'  => $this->request->str('smtp_from_name'),
            'smtp_from_email' => $this->request->str('smtp_from_email'),
            'smtp_to_email'   => $this->request->str('smtp_to_email'),
        ];

        // Só atualiza a senha se um novo valor foi informado (mantém a existente).
        $password = (string) $this->request->input('smtp_password', '');
        if ($password !== '') {
            $data['smtp_password'] = $password;
        }

        Setting::setMany($data);
        $this->done('smtp');
    }

    public function testSmtp(): void
    {
        $this->verifyCsrf();
        $to = $this->request->str('test_email') ?: (string) Setting::get('smtp_to_email');

        $validator = new Validator(['to' => $to], ['to' => 'required|email'], ['to' => 'E-mail de teste']);
        if ($validator->fails()) {
            Session::flash('error', 'Informe um e-mail de destino válido para o teste.');
            $this->redirect('admin/configuracoes?tab=smtp');
        }

        $result = Mailer::send(
            $to,
            'Teste de SMTP · Mafedo Engenharia',
            '<p>Este é um e-mail de teste enviado pelo painel da Mafedo Engenharia.</p>'
            . '<p>Se você recebeu esta mensagem, o SMTP está configurado corretamente.</p>'
        );

        if ($result['ok']) {
            Session::flash('success', 'E-mail de teste enviado com sucesso para ' . $to . '.');
        } else {
            Session::flash('error', 'Falha ao enviar: ' . $result['error']);
        }
        $this->redirect('admin/configuracoes?tab=smtp');
    }

    private function done(string $tab): void
    {
        Setting::clearCache();
        Session::flash('success', 'Configurações salvas.');
        $this->redirect('admin/configuracoes?tab=' . $tab);
    }
}
