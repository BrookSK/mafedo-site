<?php use App\Core\Auth; ?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / <a href="<?= e(url('admin/mensagens')) ?>">Mensagens</a> / Detalhe</div>
<div class="page-head"><h1><?= e($message['subject'] ?: 'Mensagem') ?></h1></div>

<div class="form-grid cols-2">
    <div class="card" style="grid-column: span 2">
        <div class="card__head">Conteúdo da mensagem</div>
        <div class="card__body">
            <p style="white-space:pre-wrap; margin:0; line-height:1.7"><?= e($message['message']) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card__head">Remetente</div>
        <div class="card__body">
            <p><strong>Nome:</strong> <?= e($message['name']) ?></p>
            <p><strong>E-mail:</strong> <a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a></p>
            <?php if (!empty($message['phone'])): ?><p><strong>Telefone:</strong> <?= e($message['phone']) ?></p><?php endif; ?>
            <?php if (!empty($message['company'])): ?><p><strong>Empresa:</strong> <?= e($message['company']) ?></p><?php endif; ?>
            <p><strong>Data:</strong> <?= e(date('d/m/Y H:i', strtotime((string) $message['created_at']))) ?></p>
            <p><strong>E-mail enviado:</strong> <?= (int) ($message['email_sent'] ?? 0) === 1 ? 'Sim' : 'Não (apenas salvo)' ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card__head">Ações</div>
        <div class="card__body btn-row">
            <a class="btn btn--primary" href="mailto:<?= e($message['email']) ?>?subject=Re: <?= e(rawurlencode((string)$message['subject'])) ?>">Responder por e-mail</a>
            <form method="post" action="<?= e(url('admin/mensagens/' . $message['id'] . '/status')) ?>" style="margin:0">
                <?= csrf_field() ?>
                <input type="hidden" name="status" value="<?= (int) $message['status'] === 1 ? '0' : '1' ?>">
                <button class="btn btn--ghost" type="submit"><?= (int) $message['status'] === 1 ? 'Marcar como não lida' : 'Marcar como lida' ?></button>
            </form>
            <?php if (Auth::can('messages.delete')): ?>
                <form method="post" action="<?= e(url('admin/mensagens/' . $message['id'] . '/excluir')) ?>" style="margin:0" data-confirm="Excluir esta mensagem?">
                    <?= csrf_field() ?>
                    <button class="btn btn--danger" type="submit">Excluir</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
