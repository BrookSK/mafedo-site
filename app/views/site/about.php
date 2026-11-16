<?php
// Textos e imagens: Painel > Textos e imagens do site > A Mafedo / Geral.
$approachList = content_lines(c('sobre.approach_list'));
?>

<!-- TOPO -->
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="about-intro">
            <div class="about-intro__text reveal">
                <?php if (c('sobre.intro_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('sobre.intro_eyebrow')) ?></p><?php endif; ?>
                <h1><?= e(c('sobre.intro_title')) ?></h1>
                <div class="about-intro__body"><?= content_paragraphs(c('sobre.intro_text')) ?></div>
            </div>
            <figure class="about-intro__media reveal" data-delay="1">
                <img src="<?= e(upload_url(c('sobre.intro_image'))) ?>" alt="<?= e(c('sobre.intro_title')) ?>" loading="lazy">
            </figure>
        </div>
    </div>
</section>

<!-- NÚMEROS -->
<?php $this->partial('site/partials/numbers'); ?>

<!-- DIFERENCIAIS (texto completo) -->
<?php $this->partial('site/partials/diffs', ['full' => true]); ?>

<!-- ABORDAGEM -->
<section class="section">
    <div class="container split split--text-first">
        <figure class="split__media reveal">
            <img src="<?= e(upload_url(c('sobre.approach_image'))) ?>" alt="<?= e(c('sobre.approach_title')) ?>" loading="lazy">
        </figure>
        <div class="split__content reveal" data-delay="1">
            <?php if (c('sobre.approach_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('sobre.approach_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('sobre.approach_title')) ?></h2>
            <?= content_paragraphs(c('sobre.approach_text'), 'muted') ?>
            <?php if ($approachList): ?>
                <ul class="list-check">
                    <?php foreach ($approachList as $item): ?><li><?= e($item) ?></li><?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (c('sobre.approach_btn') !== ''): ?>
                <div class="btn-group" style="margin-top:28px">
                    <a class="btn btn--dark" href="<?= e(url('/projetos')) ?>"><?= e(c('sobre.approach_btn')) ?> <span class="arrow">→</span></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- MISSÃO, VISÃO E VALORES -->
<?php $this->partial('site/partials/mvv'); ?>

<!-- SEGMENTOS (vêm de Serviços) -->
<?php if (!empty($services)): ?>
<section class="section">
    <div class="container">
        <div class="sx-head reveal">
            <?php if (c('sobre.segments_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('sobre.segments_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('sobre.segments_title')) ?></h2>
        </div>
        <ul class="segment-list reveal">
            <?php foreach ($services as $s): ?>
                <li><a href="<?= e(url('/servicos/' . $s['slug'])) ?>"><span><?= e($s['title']) ?></span></a><?php if (!empty($s['short_description'])): ?><small><?= e($s['short_description']) ?></small><?php endif; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php endif; ?>

<?php $this->partial('site/partials/contact-band'); ?>
