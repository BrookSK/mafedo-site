<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Logger;
use App\Core\Session;
use App\Core\Validator;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Services\Mailer;

final class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('site/contact', [
            'meta' => [
                'title'       => 'Contato · ' . Setting::get('company_name', 'Mafedo Engenharia'),
                'description' => 'Fale com a Mafedo Engenharia. Tire dúvidas, solicite orçamentos e converse sobre seu próximo projeto.',
            ],
            'errors' => Session::flash('errors') ?? [],
            'sent'   => Session::flash('contact_sent') ?? false,
        ]);
    }

    public function submit(): void
    {
        $this->verifyCsrf();

        $input = $this->request->all();
        $ip = $this->request->ip();

        // Honeypot: campo oculto que humanos não preenchem.
        if (trim((string) $this->request->input('website', '')) !== '') {
            Logger::warning('Contato bloqueado por honeypot. IP: ' . $ip);
            // Resposta "amigável" para não dar pistas ao bot.
            Session::flash('contact_sent', true);
            $this->redirect('contato');
        }

        // Rate limit por IP (anti flood).
        $messageModel = new ContactMessage();
        if ($messageModel->countRecentFromIp($ip, 10) >= 5) {
            Session::flash('error', 'Você enviou muitas mensagens recentemente. Aguarde alguns minutos.');
            $this->redirect('contato');
        }

        $validator = new Validator($input, [
            'name'    => 'required|max:160',
            'email'   => 'required|email|max:160',
            'phone'   => 'max:40',
            'company' => 'max:160',
            'subject' => 'max:200',
            'message' => 'required|max:5000',
        ], [
            'name'    => 'Nome',
            'email'   => 'E-mail',
            'message' => 'Mensagem',
        ]);

        if ($validator->fails()) {
            $this->flashOld($input);
            $this->flashErrors($validator->errors());
            Session::flash('error', 'Verifique os campos destacados e tente novamente.');
            $this->redirect('contato');
        }

        // Sanitiza entradas (strip_tags) — a exibição no admin também escapa com e().
        $data = [
            'name'       => $this->clean($this->request->str('name')),
            'email'      => mb_strtolower($this->request->str('email')),
            'phone'      => $this->clean($this->request->str('phone')),
            'company'    => $this->clean($this->request->str('company')),
            'subject'    => $this->clean($this->request->str('subject')) ?: 'Contato pelo site',
            'message'    => $this->clean((string) $this->request->input('message', '')),
            'status'     => ContactMessage::STATUS_UNREAD,
            'ip_address' => $ip,
            'user_agent' => $this->request->userAgent(),
            'email_sent' => 0,
        ];

        // 1) Sempre persiste a mensagem (mesmo se o e-mail falhar).
        $id = $messageModel->create($data);

        // 2) Tenta enviar e-mail via SMTP configurado.
        $to = (string) Setting::get('smtp_to_email') ?: (string) Setting::get('contact_email');
        $emailSent = false;
        if ($to !== '') {
            $result = Mailer::send(
                $to,
                'Novo contato pelo site: ' . $data['subject'],
                $this->buildEmailBody($data),
                $data['email']
            );
            $emailSent = $result['ok'];
            if (!$emailSent) {
                Logger::error('Contato salvo (id ' . $id . ') mas e-mail falhou: ' . $result['error']);
            }
        } else {
            Logger::warning('Contato salvo (id ' . $id . ') sem envio: e-mail de destino não configurado.');
        }

        $messageModel->markEmailSent($id, $emailSent);

        // Resposta amigável independente do envio (a mensagem já está salva).
        Session::flash('contact_sent', true);
        $this->redirect('contato');
    }

    private function clean(string $value): string
    {
        return trim(strip_tags($value));
    }

    private function buildEmailBody(array $d): string
    {
        $row = static fn (string $label, string $value): string =>
            $value === '' ? '' : '<p style="margin:4px 0"><strong>' . e($label) . ':</strong> ' . e($value) . '</p>';

        return '<div style="font-family:Arial,sans-serif;font-size:15px;color:#1b2435">'
            . '<h2 style="color:#01071F">Novo contato pelo site</h2>'
            . $row('Nome', $d['name'])
            . $row('E-mail', $d['email'])
            . $row('Telefone', $d['phone'])
            . $row('Empresa', $d['company'])
            . $row('Assunto', $d['subject'])
            . '<p style="margin:12px 0 4px"><strong>Mensagem:</strong></p>'
            . '<p style="white-space:pre-wrap;background:#f4f6fb;padding:12px;border-radius:8px">' . e($d['message']) . '</p>'
            . '</div>';
    }
}
