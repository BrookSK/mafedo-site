<?php use App\Core\Auth; ?>
<div class="page-head">
    <h1>Dashboard</h1>
    <div class="btn-row">
        <?php if (Auth::can('projects.create')): ?>
            <a class="btn btn--primary" href="<?= e(url('admin/projetos/criar')) ?>">+ Novo projeto</a>
        <?php endif; ?>
    </div>
</div>

<div class="stat-grid">
    <div class="stat"><div class="stat__label">Projetos (total)</div><div class="stat__value"><?= (int) $stats['projects_total'] ?></div></div>
    <div class="stat"><div class="stat__label">Projetos publicados</div><div class="stat__value"><?= (int) $stats['projects_published'] ?></div></div>
    <div class="stat"><div class="stat__label">Serviços ativos</div><div class="stat__value"><?= (int) $stats['services_active'] ?></div></div>
    <div class="stat"><div class="stat__label">Mensagens não lidas</div><div class="stat__value"><?= (int) $stats['messages_unread'] ?></div></div>
    <div class="stat"><div class="stat__label">Mensagens (total)</div><div class="stat__value"><?= (int) $stats['messages_total'] ?></div></div>
    <div class="stat"><div class="stat__label">Usuários</div><div class="stat__value"><?= (int) $stats['users_total'] ?></div></div>
</div>

<div class="form-grid cols-2">
    <div class="card">
        <div class="card__head">Mensagens recentes</div>
        <div class="card__body" style="padding:0">
            <?php if (empty($recentMessages)): ?>
                <div class="empty"><p>Nenhuma mensagem recebida ainda.</p></div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="data">
                        <thead><tr><th>Nome</th><th>Assunto</th><th>Data</th></tr></thead>
                        <tbody>
                        <?php foreach ($recentMessages as $m): ?>
                            <tr>
                                <td>
                                    <?php if (Auth::can('messages.view')): ?>
                                        <a href="<?= e(url('admin/mensagens/' . $m['id'])) ?>"><?= e($m['name']) ?></a>
                                    <?php else: ?><?= e($m['name']) ?><?php endif; ?>
                                    <?php if ((int) $m['status'] === 0): ?> <span class="badge badge--new">nova</span><?php endif; ?>
                                </td>
                                <td><?= e(str_excerpt((string) $m['subject'], 40)) ?></td>
                                <td><?= e(date('d/m/Y', strtotime((string) $m['created_at']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card__head">Projetos recentes</div>
        <div class="card__body" style="padding:0">
            <?php if (empty($recentProjects)): ?>
                <div class="empty"><p>Nenhum projeto cadastrado ainda.</p></div>
            <?php else: ?>
                <div class="table-wrap">
                    <table class="data">
                        <thead><tr><th>Projeto</th><th>Categoria</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($recentProjects as $p): ?>
                            <tr>
                                <td>
                                    <?php if (Auth::can('projects.edit')): ?>
                                        <a href="<?= e(url('admin/projetos/' . $p['id'] . '/editar')) ?>"><?= e($p['title']) ?></a>
                                    <?php else: ?><?= e($p['title']) ?><?php endif; ?>
                                </td>
                                <td><?= e($p['category'] ?: '—') ?></td>
                                <td>
                                    <?php if ((int) $p['status'] === 1): ?>
                                        <span class="badge badge--on">Publicado</span>
                                    <?php else: ?>
                                        <span class="badge badge--off">Rascunho</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
