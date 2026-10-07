<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\ContactMessage;

final class MessageController extends Controller
{
    private ContactMessage $messages;

    public function __construct(\App\Core\Request $request)
    {
        parent::__construct($request);
        $this->messages = new ContactMessage();
    }

    public function index(): void
    {
        $this->adminView('admin/messages/index', [
            'title'    => 'Mensagens',
            'messages' => $this->messages->latest(200),
        ]);
    }

    public function show(string $id): void
    {
        $message = $this->messages->find((int) $id);
        if ($message === null) {
            Session::flash('error', 'Mensagem não encontrada.');
            $this->redirect('admin/mensagens');
        }

        // Marca como lida ao abrir.
        if ((int) $message['status'] === ContactMessage::STATUS_UNREAD) {
            $this->messages->setStatus((int) $id, ContactMessage::STATUS_READ);
            $message['status'] = ContactMessage::STATUS_READ;
        }

        $this->adminView('admin/messages/show', [
            'title'   => 'Mensagem',
            'message' => $message,
        ]);
    }

    public function setStatus(string $id): void
    {
        $this->verifyCsrf();
        $status = (int) $this->request->input('status', 0) === 1
            ? ContactMessage::STATUS_READ
            : ContactMessage::STATUS_UNREAD;
        $this->messages->setStatus((int) $id, $status);
        Session::flash('success', 'Status atualizado.');
        $this->redirect('admin/mensagens');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        $this->messages->delete((int) $id);
        Session::flash('success', 'Mensagem excluída.');
        $this->redirect('admin/mensagens');
    }
}
