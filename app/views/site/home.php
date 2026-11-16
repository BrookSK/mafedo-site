<?php
// Todos os textos e imagens: Painel > Textos e imagens do site > Página inicial / Geral.
$waLink = whatsapp_link();
$jumps = array_filter([
    '#intro'          => c('home.jump_about'),
    '#servicos'       => c('home.jump_services'),
    '#portfolio'      => c('home.jump_projects'),
    '#valores'        => c('home.jump_values'),
    '#contato-rapido' => c('home.jump_contact'),
], static fn ($l) => trim($l) !== '');
?>

<!-- HERO -->
<section class="hero">
    <div class="hero__media">
        <img src="<?= e(upload_url(c('home.hero_image'))) ?>" alt="" fetchpriority="high">
    </div>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__inner">
            <?php if (c('home.hero_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('home.hero_eyebrow')) ?></p><?php endif; ?>
            <h1><?= e(c('home.hero_title')) ?></h1>
            <?php if (c('home.hero_subtitle') !== ''): ?><p class="lead"><?= nl2br(e(c('home.hero_subtitle'))) ?></p><?php endif; ?>
            <div class="btn-group">
                <?php if (c('home.hero_btn1') !== ''): ?>
                    <a class="btn" href="#portfolio"><?= e(c('home.hero_btn1')) ?> <span class="arrow">→</span></a>
                <?php endif; ?>
                <?php if (c('home.hero_btn2') !== ''): ?>
                    <?php if ($waLink !== ''): ?>
                        <a class="btn btn--outline" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> <?= e(c('home.hero_btn2')) ?></a>
                    <?php else: ?>
                        <a class="btn btn--outline" href="<?= e(url('/contato')) ?>"><?= e(c('home.hero_btn2')) ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($jumps): ?>
    <nav class="hero__jump" aria-label="Atalhos da página">
        <?php foreach ($jumps as $href => $label): ?><a href="<?= e($href) ?>"><?= e($label) ?></a><?php endforeach; ?>
    </nav>
    <?php endif; ?>
</section>

<!-- SOBRE A MAFEDO -->
<section class="section" id="intro">
    <div class="container split">
        <div class="split__content reveal">
            <?php if (c('home.about_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('home.about_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('home.about_title')) ?></h2>
            <?= content_paragraphs(c('home.about_text'), 'lead') ?>
            <?php if (c('home.about_btn') !== ''): ?>
                <div class="btn-group" style="margin-top:28px">
                    <a class="btn btn--dark" href="<?= e(url('/sobre')) ?>"><?= e(c('home.about_btn')) ?> <span class="arrow">→</span></a>
                </div>
            <?php endif; ?>
        </div>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(upload_url(c('home.about_image'))) ?>" alt="<?= e(c('home.about_title')) ?>" loading="lazy">
        </div>
    </div>
</section>

<!-- NÚMEROS -->
<?php $this->partial('site/partials/numbers'); ?>

<!-- SEGMENTOS -->
<?php if (!empty($featuredServices)): ?>
<section class="section section--soft" id="servicos">
    <div class="container">
        <div class="sx-head reveal">
            <?php if (c('home.services_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('home.services_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('home.services_title')) ?></h2>
        </div>
        <div class="svc-list">
            <?php foreach ($featuredServices as $i => $s): ?>
                <a class="svc-row reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                    <span class="svc-row__ico"><?= icon(service_icon_name(($s['icon'] ?? '') ?: $s['slug'] . ' ' . $s['title'])) ?></span>
                    <span>
                        <span class="svc-row__title"><?= e($s['title']) ?></span>
                        <?php if (!empty($s['short_description'])): ?><span class="svc-row__desc"><?= e($s['short_description']) ?></span><?php endif; ?>
                    </span>
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
            <?php if (c('home.projects_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('home.projects_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('home.projects_title')) ?></h2>
        </div>

        <?php if (empty($featuredProjects)): ?>
            <div class="empty-state reveal"><p><?= e(c('projetos.empty_text')) ?></p></div>
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
            <?php if (c('home.projects_btn') !== ''): ?>
                <div class="btn-group" style="margin-top:40px">
                    <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>"><?= e(c('home.projects_btn')) ?></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<!-- DIFERENCIAIS (resumo) -->
<?php $this->partial('site/partials/diffs', ['full' => false]); ?>

<!-- MISSÃO, VISÃO E VALORES -->
<?php $this->partial('site/partials/mvv'); ?>

<!-- CONTATO RÁPIDO -->
<?php $this->partial('site/partials/contact-band'); ?>
