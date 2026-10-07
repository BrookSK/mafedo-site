<?php
use App\Models\Setting;
$companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');
$logo = (string) Setting::get('company_logo', '');
$path = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/') ?: '/';
$navItems = [
    '/'         => 'Início',
    '/sobre'    => 'A Mafedo',
    '/servicos' => 'Serviços',
    '/projetos' => 'Projetos',
    '/contato'  => 'Contato',
];
$isActive = static function (string $href) use ($path): string {
    if ($href === '/') return $path === '/' ? ' is-active' : '';
    return str_starts_with($path, $href) ? ' is-active' : '';
};
?>
<header class="site-header">
    <div class="container">
        <a class="brand" href="<?= e(url('/')) ?>" aria-label="<?= e($companyName) ?> — página inicial">
            <?php if ($logo !== ''): ?>
                <img src="<?= e(upload_url($logo)) ?>" alt="<?= e($companyName) ?>">
            <?php else: ?>
                MAFEDO<span>.</span>
            <?php endif; ?>
        </a>

        <nav class="nav" aria-label="Navegação principal">
            <?php foreach ($navItems as $href => $label): ?>
                <a class="<?= trim($isActive($href)) ?>" href="<?= e(url($href)) ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="header-cta">
            <a class="btn" href="<?= e(url('/contato')) ?>">Fale com a Mafedo</a>
            <button class="nav-toggle" aria-label="Abrir menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
