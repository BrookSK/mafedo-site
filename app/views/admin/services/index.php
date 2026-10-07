<?php use App\Core\Auth; ?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Serviços</div>
<div class="page-head">
    <h1>Serviços</h1>
    <?php if (Auth::can('services.create')): ?>
        <a class="btn btn--primary" href="<?= e(url('admin/servicos/criar')) ?>">+ Novo serviço</a>
    <?php endif; ?>
</div>

<div class="card">
    <?php if (empty($services)): ?>
        <div class="card__body">
            <div class="empty">
                <h3>Nenhum serviço cadastrado</h3>
                <p>Cadastre os serviços da Mafedo para exibi-los no site.</p>
                <?php if (Auth::can('services.create')): ?>
                    <a class="btn btn--primary" href="<?= e(url('admin/servicos/criar')) ?>">Cadastrar serviço</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th>Ordem</th><th>Título</th><th>Destaque</th><th>Status</th><th style="text-align:right">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($services as $s): ?>
                    <tr>
                        <td><?= (int) $s['sort_order'] ?></td>
                        <td>
                            <strong><?= e($s['title']) ?></strong><br>
                            <small style="color:var(--text-muted)"><?= e($s['slug']) ?></small>
                        </td>
                        <td><?= (int) $s['featured'] === 1 ? '<span class="badge badge--star">Destaque</span>' : '—' ?></td>
                        <td>
                            <?= (int) $s['status'] === 1
                                ? '<span class="badge badge--on">Ativo</span>'
                                : '<span class="badge badge--off">Inativo</span>' ?>
                        </td>
                        <td>
                            <div class="actions" style="justify-content:flex-end">
                                <?php if (Auth::can('services.edit')): ?>
                                    <a class="btn btn--ghost btn--sm" href="<?= e(url('admin/servicos/' . $s['id'] . '/editar')) ?>">Editar</a>
                                    <form method="post" action="<?= e(url('admin/servicos/' . $s['id'] . '/toggle')) ?>" style="margin:0">
                                        <?= csrf_field() ?>
                                        <button class="btn btn--ghost btn--sm" type="submit"><?= (int) $s['status'] === 1 ? 'Desativar' : 'Ativar' ?></button>
                                    </form>
                                <?php endif; ?>
                                <?php if (Auth::can('services.delete')): ?>
                                    <form method="post" action="<?= e(url('admin/servicos/' . $s['id'] . '/excluir')) ?>" style="margin:0" data-confirm="Excluir este serviço?">
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
