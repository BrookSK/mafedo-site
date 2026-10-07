<?php
use App\Models\Service;
use App\Models\Setting;

$companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');
$logo = (string) (Setting::get('logo_footer') ?: Setting::get('company_logo', ''));
$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$hours = (string) Setting::get('business_hours', '');
$socials = [
    'Instagram' => (string) Setting::get('social_instagram', ''),
    'Facebook'  => (string) Setting::get('social_facebook', ''),
    'LinkedIn'  => (string) Setting::get('social_linkedin', ''),
    'YouTube'   => (string) Setting::get('social_youtube', ''),
];
$socialAbbr = ['Instagram' => 'IG', 'Facebook' => 'FB', 'LinkedIn' => 'IN', 'YouTube' => 'YT'];

try { $footerServices = (new Service())->active(); } catch (\Throwable) { $footerServices = []; }
$footerServices = array_slice($footerServices, 0, 5);

// Fallback: se ainda não há serviços cadastrados, mostra áreas de atuação
// típicas (links para a página de serviços) para o rodapé não ficar vazio.
$serviceLinks = [];
if (!empty($footerServices)) {
    foreach ($footerServices as $s) {
        $serviceLinks[] = ['label' => $s['title'], 'href' => url('/servicos/' . $s['slug'])];
    }
} else {
    foreach (['Projetos Estruturais', 'Gestão de Obras', 'Consultoria Técnica', 'Reformas e Retrofit'] as $label) {
        $serviceLinks[] = ['label' => $label, 'href' => url('/servicos')];
    }
}
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="<?= e(url('/')) ?>">
                    <?php if ($logo !== ''): ?>
                        <img src="<?= e(upload_url($logo)) ?>" alt="<?= e($companyName) ?>" class="brand__img">
                    <?php else: ?>
                        <span class="brand__word">MAFEDO<span class="brand__dot">.</span></span>
                        <span class="brand__tag">ENGENHARIA</span>
                    <?php endif; ?>
                </a>
                <p class="footer-desc">Engenharia com rigor técnico, segurança e capacidade de execução para transformar projetos em resultados.</p>
                <div class="socials">
                    <?php
                    $anySocial = false;
                    foreach ($socials as $name => $urlSocial):
                        if ($urlSocial === '') continue;
                        $anySocial = true; ?>
                        <a href="<?= e($urlSocial) ?>" target="_blank" rel="noopener" aria-label="<?= e($name) ?>"><?= e($socialAbbr[$name]) ?></a>
                    <?php endforeach; ?>
                    <?php if (!$anySocial): ?>
                        <a href="https://www.instagram.com/mafedo_engenharia/" target="_blank" rel="noopener" aria-label="Instagram">IG</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="footer-col">
                <h4>Institucional</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(url('/')) ?>">Início</a></li>
                    <li><a href="<?= e(url('/sobre')) ?>">A Mafedo</a></li>
                    <li><a href="<?= e(url('/projetos')) ?>">Projetos</a></li>
                    <li><a href="<?= e(url('/contato')) ?>">Contato</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Serviços</h4>
                <ul class="footer-links">
                    <?php foreach ($serviceLinks as $link): ?>
                        <li><a href="<?= e($link['href']) ?>"><?= e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contato</h4>
                <ul class="footer-links">
                    <li><a href="tel:<?= e($phone !== '' ? preg_replace('/\D+/', '', $phone) : '') ?>"><?= e($phone !== '' ? $phone : 'Telefone em breve') ?></a></li>
                    <li><a href="mailto:<?= e($email !== '' ? $email : 'contato@mafedo.com.br') ?>"><?= e($email !== '' ? $email : 'contato@mafedo.com.br') ?></a></li>
                    <?php if ($address !== ''): ?><li><?= nl2br(e($address)) ?></li><?php else: ?><li>São Paulo, SP</li><?php endif; ?>
                    <?php if ($hours !== ''): ?><li><?= e($hours) ?></li><?php endif; ?>
                    <li style="margin-top:8px"><a href="<?= e(url('/contato')) ?>" class="footer-cta-link">Fale com a Mafedo →</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= e($companyName) ?>. Todos os direitos reservados.</span>
            <span>
                <a href="<?= e(url('/politica-de-privacidade')) ?>">Política de Privacidade</a>
                &nbsp;·&nbsp;
                <a href="<?= e(url('/termos-de-uso')) ?>">Termos de Uso</a>
                &nbsp;·&nbsp;
                <a class="footer-restrita" href="<?= e(url('/admin')) ?>">Área Restrita</a>
            </span>
        </div>
    </div>
</footer>
