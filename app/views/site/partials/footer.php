<?php
use App\Models\Service;
use App\Models\Setting;

$companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');
$logo = (string) (Setting::get('logo_footer') ?: Setting::get('company_logo') ?: '/assets/images/brand/logo-horizontal-branco.png');
$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$hours = (string) Setting::get('business_hours', '');
$waLink = whatsapp_link();
$socials = [
    'Instagram' => (string) Setting::get('social_instagram', ''),
    'Facebook'  => (string) Setting::get('social_facebook', ''),
    'LinkedIn'  => (string) Setting::get('social_linkedin', ''),
    'YouTube'   => (string) Setting::get('social_youtube', ''),
];
$socialIcon = ['Instagram' => 'instagram', 'Facebook' => 'facebook', 'LinkedIn' => 'linkedin', 'YouTube' => 'youtube'];

try { $footerServices = (new Service())->active(); } catch (\Throwable) { $footerServices = []; }
$footerServices = array_slice($footerServices, 0, 5);

$serviceLinks = [];
foreach ($footerServices as $s) {
    $serviceLinks[] = ['label' => $s['title'], 'href' => url('/servicos/' . $s['slug'])];
}

// Links institucionais usam os mesmos rótulos do menu (editáveis no painel).
$institutional = array_filter([
    '/'         => c('geral.nav_home'),
    '/sobre'    => c('geral.nav_about'),
    '/projetos' => c('geral.nav_projects'),
    '/contato'  => c('geral.nav_contact'),
], static fn ($l) => trim($l) !== '');
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="<?= e(url('/')) ?>">
                    <img src="<?= e(upload_url($logo)) ?>" alt="<?= e($companyName) ?>" class="brand__img">
                </a>
                <?php if (c('geral.footer_text') !== ''): ?><p class="footer-desc"><?= nl2br(e(c('geral.footer_text'))) ?></p><?php endif; ?>
                <div class="socials">
                    <?php
                    $anySocial = false;
                    foreach ($socials as $name => $urlSocial):
                        if ($urlSocial === '') continue;
                        $anySocial = true; ?>
                        <a href="<?= e($urlSocial) ?>" target="_blank" rel="noopener" aria-label="<?= e($name) ?>"><?= icon($socialIcon[$name] ?? 'default') ?></a>
                    <?php endforeach; ?>
                    <?php if (!$anySocial): ?>
                        <a href="<?= e(instagram_url()) ?>" target="_blank" rel="noopener" aria-label="Instagram"><?= icon('instagram') ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="footer-col">
                <h4><?= e(c('geral.footer_col1')) ?></h4>
                <ul class="footer-links">
                    <?php foreach ($institutional as $href => $label): ?>
                        <li><a href="<?= e(url($href)) ?>"><?= e($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if ($serviceLinks): ?>
            <div class="footer-col">
                <h4><?= e(c('geral.footer_col2')) ?></h4>
                <ul class="footer-links">
                    <?php foreach ($serviceLinks as $link): ?>
                        <li><a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="footer-col">
                <h4><?= e(c('geral.footer_col3')) ?></h4>
                <ul class="footer-links">
                    <?php if ($phone !== ''): ?><li><a href="tel:<?= e(preg_replace('/\D+/', '', $phone)) ?>"><?= e($phone) ?></a></li><?php endif; ?>
                    <?php if ($waLink !== ''): ?><li><a href="<?= e($waLink) ?>" target="_blank" rel="noopener">WhatsApp</a></li><?php endif; ?>
                    <li><a href="mailto:<?= e($email !== '' ? $email : 'contato@mafedo.com.br') ?>"><?= e($email !== '' ? $email : 'contato@mafedo.com.br') ?></a></li>
                    <?php if ($address !== ''): ?><li><?= nl2br(e($address)) ?></li><?php else: ?><li>São Paulo, SP</li><?php endif; ?>
                    <?php if ($hours !== ''): ?><li><?= e($hours) ?></li><?php endif; ?>
                    <?php if (c('geral.footer_cta') !== ''): ?><li style="margin-top:8px"><a href="<?= e(url('/contato')) ?>" class="footer-cta-link"><?= e(c('geral.footer_cta')) ?> →</a></li><?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= e($companyName) ?>. <?= e(c('geral.footer_rights')) ?></span>
            <span>
                <a href="<?= e(url('/politica-de-privacidade')) ?>"><?= e(c('legal.privacy_title')) ?></a>
                &nbsp;·&nbsp;
                <a href="<?= e(url('/termos-de-uso')) ?>"><?= e(c('legal.terms_title')) ?></a>
                &nbsp;·&nbsp;
                <a class="footer-restrita" href="<?= e(url('/admin')) ?>">Área Restrita</a>
            </span>
        </div>
    </div>
</footer>
