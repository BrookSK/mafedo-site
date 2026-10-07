<?php
$isEdit = $user !== null;
$action = $isEdit ? url('admin/usuarios/' . $user['id']) : url('admin/usuarios');
$val = static fn (string $k, string $d = '') => e($isEdit ? ($user[$k] ?? $d) : old($k, $d));
$err = static fn (string $k) => isset($errors[$k]) ? '<span class="field__error">' . e($errors[$k]) . '</span>' : '';
$hasErr = static fn (string $k) => isset($errors[$k]) ? ' field--error' : '';
?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / <a href="<?= e(url('admin/usuarios')) ?>">Usuários</a> / <?= $isEdit ? 'Editar' : 'Novo' ?></div>
<div class="page-head"><h1><?= $isEdit ? 'Editar usuário' : 'Novo usuário' ?></h1></div>

<form method="post" action="<?= e($action) ?>">
    <?= csrf_field() ?>
    <div class="form-grid cols-2">
        <div class="card">
            <div class="card__head">Dados do usuário</div>
            <div class="card__body form-grid">
                <div class="field<?= $hasErr('name') ?>">
                    <label for="name">Nome *</label>
                    <input class="input" id="name" name="name" value="<?= $val('name') ?>" required>
                    <?= $err('name') ?>
                </div>
                <div class="field<?= $hasErr('email') ?>">
                    <label for="email">E-mail *</label>
                    <input class="input" type="email" id="email" name="email" value="<?= $val('email') ?>" required>
                    <?= $err('email') ?>
                </div>
                <label class="switch">
                    <input type="checkbox" name="status" value="1" <?= ($isEdit ? (int)$user['status']===1 : true) ? 'checked' : '' ?>>
                    Ativo
                </label>
            </div>
        </div>

        <div class="card">
            <div class="card__head">Senha <?= $isEdit ? '(deixe em branco para manter)' : '' ?></div>
            <div class="card__body form-grid">
                <div class="field<?= $hasErr('password') ?>">
                    <label for="password">Senha</label>
                    <input class="input" type="password" id="password" name="password" autocomplete="new-password" <?= $isEdit ? '' : 'required' ?>>
                    <span class="hint">Mínimo de 8 caracteres.</span>
                    <?= $err('password') ?>
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input class="input" type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>
        </div>

        <div class="card" style="grid-column: span 2">
            <div class="card__head">Perfis de acesso</div>
            <div class="card__body">
                <div class="form-grid cols-3">
                    <?php foreach ($roles as $role): ?>
                        <label class="switch">
                            <input type="checkbox" name="roles[]" value="<?= (int) $role['id'] ?>" <?= in_array((int)$role['id'], $userRoles, true) ? 'checked' : '' ?>>
                            <span>
                                <strong><?= e($role['name']) ?></strong>
                                <?php if (!empty($role['description'])): ?><br><small style="color:var(--text-muted)"><?= e($role['description']) ?></small><?php endif; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-row" style="margin-top:20px">
        <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar' : 'Criar usuário' ?></button>
        <a class="btn btn--ghost" href="<?= e(url('admin/usuarios')) ?>">Cancelar</a>
    </div>
</form>
