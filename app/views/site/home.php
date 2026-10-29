<?php use App\Models\Setting; ?>

<!-- HERO -->
<section class="hero">
    <div class="hero__media">
        <?php if (!empty($hero['image'])): ?>
            <img src="<?= e(upload_url($hero['image'])) ?>" alt="" fetchpriority="high">
        <?php else: ?>
            <img src="<?= e(asset('images/site/home-hero.jpg')) ?>" alt="" fetchpriority="high">
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__inner">
            <p class="eyebrow"><?= e($hero['eyebrow']) ?></p>
            <h1><?= e($hero['title']) ?></h1>
            <?php if (!empty($hero['subtitle'])): ?><p class="lead"><?= e($hero['subtitle']) ?></p><?php endif; ?>
            <div class="btn-group">
                <a class="btn" href="#portfolio">Ver portfólio <span class="arrow">→</span></a>
                <?php if (whatsapp_link() !== ''): ?>
                    <a class="btn btn--outline" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> Falar no WhatsApp</a>
                <?php else: ?>
                    <a class="btn btn--outline" href="<?= e(url('/contato')) ?>">Fale conosco</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Atalhos: o visitante chega a qualquer parte da página em um clique -->
    <nav class="hero__jump" aria-label="Atalhos da página">
        <a href="#intro">A Mafedo</a>
        <a href="#servicos">Serviços</a>
        <a href="#portfolio">Portfólio</a>
        <a href="#valores">Missão e valores</a>
        <a href="#contato-rapido">Contato</a>
    </nav>
</section>

<!-- A MAFEDO -->
<section class="section" id="intro">
    <div class="container split">
        <div class="split__content reveal">
            <p class="eyebrow">A Mafedo</p>
            <h2>Soluções de engenharia com precisão e compromisso.</h2>
            <?php if (!empty($aboutText)): ?>
                <p class="lead"><?= e($aboutText) ?></p>
            <?php endif; ?>
            <div class="btn-group" style="margin-top:28px">
                <a class="btn btn--dark" href="<?= e(url('/sobre')) ?>">Conheça a Mafedo <span class="arrow">→</span></a>
            </div>
        </div>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(Setting::get('home_hero_image') ? upload_url((string) Setting::get('home_hero_image')) : asset('images/site/home-intro.jpg')) ?>" alt="Equipe de engenharia da Mafedo em obra" loading="lazy">
        </div>
    </div>
</section>

<!-- SERVIÇOS -->
<?php if (!empty($featuredServices)): ?>
<section class="section section--soft" id="servicos">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow">O que fazemos</p>
            <h2>Do projeto à entrega.</h2>
        </div>
        <div class="svc-list">
            <?php foreach ($featuredServices as $i => $s): ?>
                <a class="svc-row reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                    <span class="svc-row__ico"><?= icon(service_icon_name($s['slug'] . ' ' . $s['title'])) ?></span>
                    <span class="svc-row__title"><?= e($s['title']) ?></span>
                    <span class="svc-row__go" aria-hidden="true"><?= icon('arrow') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- PORTFÓLIO -->
<section class="section" id="portfolio">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow">Portfólio</p>
            <h2>Projetos que traduzem capacidade de execução.</h2>
        </div>

        <?php if (empty($featuredProjects)): ?>
            <div class="empty-state reveal">
                <p>Os projetos em destaque aparecerão aqui assim que forem cadastrados no painel.</p>
            </div>
        <?php else: ?>
            <div class="proj-editorial">
                <?php foreach ($featuredProjects as $i => $p): ?>
                    <a class="proj-item reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                        <img src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                        <span class="proj-item__cap">
                            <?php if (!empty($p['category'])): ?><span class="proj-item__cat"><?= e($p['category']) ?></span><?php endif; ?>
                            <span class="proj-item__title"><?= e($p['title']) ?></span>
                            <?php if (!empty($p['location'])): ?><span class="proj-item__meta"><?= e($p['location']) ?><?= !empty($p['year']) ? ' · ' . e($p['year']) : '' ?></span><?php endif; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="btn-group" style="margin-top:40px">
                <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>">Ver todos os projetos</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- MISSÃO, VISÃO E VALORES -->
<section class="section section--soft" id="valores">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow">O que nos guia</p>
            <h2>Missão, visão e valores.</h2>
        </div>
        <?php $this->partial('site/partials/mvv'); ?>
    </div>
</section>

<!-- DIFERENCIAIS -->
<section class="section">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow">Por que a Mafedo</p>
            <h2>O que sustenta cada entrega.</h2>
        </div>
        <div class="diff-grid diff-grid--light">
            <?php
            $diffs = [
                ['people', 'Equipe especializada', 'Profissionais capacitados para execução dentro do mais elevado padrão de qualidade.'],
                ['gear', 'Gerenciamento', 'Rotina coordenada com foco em qualidade, alinhamento e redução de custos.'],
                ['clock', 'Prazo', 'Uso eficiente dos recursos para entregar atendendo às expectativas do cliente.'],
                ['shield', 'Ética e confiança', 'Honestidade e compromisso em cada relação com clientes e parceiros.'],
            ];
            foreach ($diffs as $i => $d): ?>
                <div class="diff reveal" data-delay="<?= $i % 3 ?>">
                    <span class="diff__ico"><?= icon($d[0]) ?></span>
                    <h3><?= e($d[1]) ?></h3>
                    <p class="muted"><?= e($d[2]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CONTATO RÁPIDO -->
<?php $this->partial('site/partials/contact-band'); ?>
