<?php
use App\Models\Setting;

$meta = $meta ?? [];
$companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');
$siteTitle = (string) Setting::get('seo_site_title', $companyName);

$pageTitle = $meta['title'] ?? $siteTitle;
$metaDesc = $meta['description'] ?? (string) Setting::get('seo_meta_description', '');
$canonical = $meta['canonical'] ?? (base_url() . ($_SERVER['REQUEST_URI'] ?? '/'));
$canonical = strtok($canonical, '?'); // remove querystring do canonical
$ogImage = $meta['og_image'] ?? (string) Setting::get('seo_og_image', '');
$ogType = $meta['og_type'] ?? 'website';
$ga = (string) Setting::get('seo_google_analytics', '');
$searchConsole = (string) Setting::get('seo_search_console', '');
$structured = $meta['structured'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#01071F">
    <title><?= e($pageTitle) ?></title>
    <?php if ($metaDesc !== ''): ?><meta name="description" content="<?= e($metaDesc) ?>"><?php endif; ?>
    <link rel="canonical" href="<?= e($canonical) ?>">
    <?php if ($searchConsole !== ''): ?><meta name="google-site-verification" content="<?= e($searchConsole) ?>"><?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:site_name" content="<?= e($companyName) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDesc) ?>">
    <meta property="og:type" content="<?= e($ogType) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <?php if ($ogImage !== ''): ?><meta property="og:image" content="<?= e($ogImage) ?>"><?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="<?= e(Setting::get('company_favicon') ? upload_url((string) Setting::get('company_favicon')) : asset('favicon.svg')) ?>">
    <link rel="apple-touch-icon" href="<?= e(asset('favicon.svg')) ?>">
    <link rel="manifest" href="<?= e(base_url()) ?>/site.webmanifest">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">

    <?php if ($structured !== null): ?>
        <script type="application/ld+json"><?= json_encode($structured, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
    <?php endif; ?>

    <?php if ($ga !== ''): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($ga) ?>"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e($ga) ?>');</script>
    <?php endif; ?>
</head>
<body>
    <?php $this->partial('site/partials/header'); ?>

    <main id="conteudo">
        <?= $this->content() ?>
    </main>

    <?php $this->partial('site/partials/footer'); ?>
    <?php $this->partial('site/partials/floats'); ?>

    <script src="<?= e(asset('js/site.js')) ?>" defer></script>
</body>
</html>
