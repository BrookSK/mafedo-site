<?php
use App\Models\Service;
use App\Models\Setting;

$companyName = (string) Setting::get('company_name', 'Mafedo Engenharia');
$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$socials = [
    'Instagram' => (string) Setting::get('social_instagram', ''),
    'Facebook'  => (string) Setting::get('social_facebook', ''),
    'LinkedIn'  => (string) Setting::get('social_linkedin', ''),
    'YouTube'   => (string) Setting::get('social_youtube', ''),
];
try { $footerServices = (new Service())->active(); } catch (\Throwable) { $footerServices = []; }
$footerServices = array_slice($footerServices, 0, 5);
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a class="brand" href="<?= e(url('/')) ?>">
                    <img src="<?= e(asset('images/brand/logo-azul.png')) ?>" alt="<?= e($companyName) ?>" class="brand__mark">
                    <span class="brand__word">Mafedo<em>engenharia</em></span>
                </a>
                <p class="footer-desc">Engenharia com rigor técnico, segurança e capacidade de execução para transformar projetos em resultados.</p>
                <?php if (array_filter($socials)): ?>
                    <div class="socials">
                        <?php foreach ($socials as $name => $urlSocial): if ($urlSocial === '') continue; ?>
                            <a href="<?= e($urlSocial) ?>" target="_blank" rel="noopener" aria-label="<?= e($name) ?>"><?= e(mb_substr($name, 0, 2)) ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <h4>Institucional</h4>
                <ul class="footer-links">
                    <li><a href="<?= e(url('/sobre')) ?>">A Mafedo</a></li>
                    <li><a href="<?= e(url('/servicos')) ?>">Serviços</a></li>
                    <li><a href="<?= e(url('/projetos')) ?>">Projetos</a></li>
                    <li><a href="<?= e(url('/contato')) ?>">Contato</a></li>
                </ul>
            </div>

            <div>
                <h4>Serviços</h4>
                <ul class="footer-links">
                    <?php if (empty($footerServices)): ?>
                        <li><a href="<?= e(url('/servicos')) ?>">Ver serviços</a></li>
                    <?php else: foreach ($footerServices as $s): ?>
                        <li><a href="<?= e(url('/servicos/' . $s['slug'])) ?>"><?= e($s['title']) ?></a></li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>

            <div>
                <h4>Contato</h4>
                <ul class="footer-links">
                    <?php if ($phone !== ''): ?><li><a href="tel:<?= e(preg_replace('/\D+/', '', $phone)) ?>"><?= e($phone) ?></a></li><?php endif; ?>
                    <?php if ($email !== ''): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
                    <?php if ($address !== ''): ?><li><?= e($address) ?></li><?php endif; ?>
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
