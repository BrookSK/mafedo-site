<?php use App\Core\Auth; ?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Usuários</div>
<div class="page-head">
    <h1>Usuários</h1>
    <?php if (Auth::can('users.create')): ?>
        <a class="btn btn--primary" href="<?= e(url('admin/usuarios/criar')) ?>">+ Novo usuário</a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data">
            <thead><tr><th>Nome</th><th>E-mail</th><th>Perfis</th><th>Status</th><th style="text-align:right">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><strong><?= e($u['name']) ?></strong><?= (int)$u['id'] === Auth::id() ? ' <span class="badge badge--new">você</span>' : '' ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td>
                        <div class="chips">
                            <?php foreach ($u['roles'] as $r): ?><span class="chip"><?= e($r) ?></span><?php endforeach; ?>
                            <?php if (empty($u['roles'])): ?><span style="color:var(--text-muted)">—</span><?php endif; ?>
                        </div>
                    </td>
                    <td><?= (int) $u['status'] === 1 ? '<span class="badge badge--on">Ativo</span>' : '<span class="badge badge--off">Inativo</span>' ?></td>
                    <td>
                        <div class="actions" style="justify-content:flex-end">
                            <?php if (Auth::can('users.edit')): ?>
                                <a class="btn btn--ghost btn--sm" href="<?= e(url('admin/usuarios/' . $u['id'] . '/editar')) ?>">Editar</a>
                            <?php endif; ?>
                            <?php if (Auth::can('users.delete') && (int)$u['id'] !== Auth::id()): ?>
                                <form method="post" action="<?= e(url('admin/usuarios/' . $u['id'] . '/excluir')) ?>" style="margin:0" data-confirm="Excluir este usuário?">
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
</div>
