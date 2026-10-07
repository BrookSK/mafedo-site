<?php
use App\Core\Auth;
use App\Core\Session;
use App\Models\ContactMessage;

$title = $title ?? 'Painel';
$user = Auth::user();
$path = $_SERVER['REQUEST_URI'] ?? '';
$unread = 0;
try { $unread = (new ContactMessage())->unreadCount(); } catch (\Throwable) {}

$active = static function (string $needle) use ($path): string {
    return str_contains($path, $needle) ? ' is-active' : '';
};
$success = Session::flash('success');
$error = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title) ?> · Painel Mafedo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
<div class="admin-shell">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar__brand">MAFEDO<span>.</span> Admin</div>
        <nav class="sidebar__nav">
            <a class="sidebar__link<?= $path === url('admin') || rtrim($path,'/') === '/admin' ? ' is-active' : '' ?>" href="<?= e(url('admin')) ?>">Dashboard</a>

            <?php if (Auth::can('projects.view')): ?>
                <div class="sidebar__section">Conteúdo</div>
                <a class="sidebar__link<?= $active('/admin/projetos') ?>" href="<?= e(url('admin/projetos')) ?>">Projetos</a>
            <?php endif; ?>
            <?php if (Auth::can('services.view')): ?>
                <a class="sidebar__link<?= $active('/admin/servicos') ?>" href="<?= e(url('admin/servicos')) ?>">Serviços</a>
            <?php endif; ?>
            <?php if (Auth::can('messages.view')): ?>
                <a class="sidebar__link<?= $active('/admin/mensagens') ?>" href="<?= e(url('admin/mensagens')) ?>">
                    Mensagens
                    <?php if ($unread > 0): ?><span class="sidebar__badge"><?= (int) $unread ?></span><?php endif; ?>
                </a>
            <?php endif; ?>

            <?php if (Auth::can('users.view') || Auth::can('settings.view')): ?>
                <div class="sidebar__section">Administração</div>
            <?php endif; ?>
            <?php if (Auth::can('users.view')): ?>
                <a class="sidebar__link<?= $active('/admin/usuarios') ?>" href="<?= e(url('admin/usuarios')) ?>">Usuários</a>
            <?php endif; ?>
            <?php if (Auth::can('settings.view')): ?>
                <a class="sidebar__link<?= $active('/admin/configuracoes') ?>" href="<?= e(url('admin/configuracoes')) ?>">Configurações</a>
            <?php endif; ?>

            <div class="sidebar__section">Site</div>
            <a class="sidebar__link" href="<?= e(url('/')) ?>" target="_blank" rel="noopener">Ver site ↗</a>
        </nav>
    </aside>
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="admin-main">
        <header class="topbar">
            <button class="topbar__toggle" id="sidebarToggle" aria-label="Abrir menu">☰</button>
            <div class="topbar__title"><?= e($title) ?></div>
            <div class="topbar__spacer"></div>
            <div class="topbar__user">
                <span><?= e($user['name'] ?? '') ?></span>
                <span class="topbar__avatar"><?= e(mb_strtoupper(mb_substr((string)($user['name'] ?? '?'), 0, 1))) ?></span>
                <form method="post" action="<?= e(url('admin/logout')) ?>" style="margin:0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn--ghost btn--sm">Sair</button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            <?php if ($success): ?><div class="alert alert--success"><?= e($success) ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>
            <?= $this->content() ?>
        </main>
    </div>
</div>

<script>
(function () {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var backdrop = document.getElementById('sidebarBackdrop');
    function close() { sidebar.classList.remove('is-open'); backdrop.classList.remove('is-open'); }
    if (toggle) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('is-open');
            backdrop.classList.toggle('is-open');
        });
    }
    if (backdrop) backdrop.addEventListener('click', close);

    // Confirmação para ações destrutivas
    document.querySelectorAll('form[data-confirm]').forEach(function (f) {
        f.addEventListener('submit', function (e) {
            if (!window.confirm(f.getAttribute('data-confirm'))) e.preventDefault();
        });
    });
})();
</script>
</body>
</html>
