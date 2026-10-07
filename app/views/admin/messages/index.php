<?php use App\Core\Auth; use App\Models\ContactMessage; ?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Mensagens</div>
<div class="page-head"><h1>Mensagens de contato</h1></div>

<div class="card">
    <?php if (empty($messages)): ?>
        <div class="card__body"><div class="empty"><h3>Nenhuma mensagem</h3><p>As mensagens enviadas pelo formulário de contato aparecerão aqui.</p></div></div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data">
                <thead><tr><th>Status</th><th>Nome</th><th>Assunto</th><th>E-mail</th><th>Data</th><th style="text-align:right">Ações</th></tr></thead>
                <tbody>
                <?php foreach ($messages as $m): ?>
                    <?php $unread = (int) $m['status'] === ContactMessage::STATUS_UNREAD; ?>
                    <tr style="<?= $unread ? 'font-weight:600' : '' ?>">
                        <td><?= $unread ? '<span class="badge badge--new">Nova</span>' : '<span class="badge badge--on">Lida</span>' ?></td>
                        <td><a href="<?= e(url('admin/mensagens/' . $m['id'])) ?>"><?= e($m['name']) ?></a></td>
                        <td><?= e(str_excerpt((string) $m['subject'], 40)) ?></td>
                        <td><?= e($m['email']) ?></td>
                        <td><?= e(date('d/m/Y H:i', strtotime((string) $m['created_at']))) ?></td>
                        <td>
                            <div class="actions" style="justify-content:flex-end">
                                <a class="btn btn--ghost btn--sm" href="<?= e(url('admin/mensagens/' . $m['id'])) ?>">Ver</a>
                                <?php if (Auth::can('messages.delete')): ?>
                                    <form method="post" action="<?= e(url('admin/mensagens/' . $m['id'] . '/excluir')) ?>" style="margin:0" data-confirm="Excluir esta mensagem?">
                                        <?= csrf_field() ?>
                                        <button class="btn btn--danger btn--sm" type="submit">Excluir</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
