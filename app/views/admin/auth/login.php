<div class="auth-brand">MAFEDO<span>.</span></div>
<p class="auth-sub">Área restrita · Painel administrativo</p>

<form method="post" action="<?= e(url('admin/login')) ?>" class="form-grid" novalidate>
    <?= csrf_field() ?>
    <div class="field">
        <label for="email">E-mail</label>
        <input class="input" type="email" id="email" name="email" value="<?= e(old('email')) ?>" autocomplete="username" required autofocus>
    </div>
    <div class="field">
        <label for="password">Senha</label>
        <input class="input" type="password" id="password" name="password" autocomplete="current-password" required>
    </div>
    <button type="submit" class="btn btn--primary btn--block">Entrar</button>
</form>
