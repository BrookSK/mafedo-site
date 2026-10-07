<?php use App\Core\Auth; ?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Projetos</div>
<div class="page-head">
    <h1>Projetos</h1>
    <?php if (Auth::can('projects.create')): ?>
        <a class="btn btn--primary" href="<?= e(url('admin/projetos/criar')) ?>">+ Novo projeto</a>
    <?php endif; ?>
</div>

<div class="card">
    <?php if (empty($projects)): ?>
        <div class="card__body">
            <div class="empty">
                <h3>Nenhum projeto cadastrado</h3>
                <p>O portfólio é um dos principais destaques do site. Cadastre os projetos da Mafedo.</p>
                <?php if (Auth::can('projects.create')): ?>
                    <a class="btn btn--primary" href="<?= e(url('admin/projetos/criar')) ?>">Cadastrar projeto</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data">
                <thead>
                <tr>
                    <th></th><th>Projeto</th><th>Categoria</th><th>Local</th><th>Destaque</th><th>Status</th><th style="text-align:right">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($projects as $p): ?>
                    <tr>
                        <td><img class="thumb" src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="" loading="lazy"></td>
                        <td>
                            <strong><?= e($p['title']) ?></strong><br>
                            <small style="color:var(--text-muted)"><?= e($p['slug']) ?></small>
                        </td>
                        <td><?= e($p['category'] ?: '—') ?></td>
                        <td><?= e($p['location'] ?: '—') ?></td>
                        <td><?= (int) $p['featured'] === 1 ? '<span class="badge badge--star">Destaque</span>' : '—' ?></td>
                        <td><?= (int) $p['status'] === 1 ? '<span class="badge badge--on">Publicado</span>' : '<span class="badge badge--off">Rascunho</span>' ?></td>
                        <td>
                            <div class="actions" style="justify-content:flex-end">
                                <?php if (Auth::can('projects.edit')): ?>
                                    <a class="btn btn--ghost btn--sm" href="<?= e(url('admin/projetos/' . $p['id'] . '/editar')) ?>">Editar</a>
                                    <form method="post" action="<?= e(url('admin/projetos/' . $p['id'] . '/toggle')) ?>" style="margin:0">
                                        <?= csrf_field() ?>
                                        <button class="btn btn--ghost btn--sm" type="submit"><?= (int) $p['status'] === 1 ? 'Despublicar' : 'Publicar' ?></button>
                                    </form>
                                <?php endif; ?>
                                <?php if (Auth::can('projects.delete')): ?>
                                    <form method="post" action="<?= e(url('admin/projetos/' . $p['id'] . '/excluir')) ?>" style="margin:0" data-confirm="Excluir este projeto e todas as suas imagens?">
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
