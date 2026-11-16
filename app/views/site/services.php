<?php
// Textos e imagens: Painel > Textos e imagens do site > Segmentos (Serviços).
// A lista em si vem de Painel > Serviços.
$steps = content_items('servicos', 'processo_itens');
?>
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-head reveal">
            <?php if (c('servicos.head_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('servicos.head_eyebrow')) ?></p><?php endif; ?>
            <h1><?= e(c('servicos.head_title')) ?></h1>
            <?php if (c('servicos.head_text') !== ''): ?><p class="lead"><?= nl2br(e(c('servicos.head_text'))) ?></p><?php endif; ?>
        </div>

        <?php if (empty($services)): ?>
            <div class="empty-state reveal">
                <p>Os segmentos serão exibidos aqui assim que forem cadastrados no painel.</p>
            </div>
        <?php else: ?>
            <div class="svc-list">
                <?php foreach ($services as $i => $s): ?>
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
        <?php endif; ?>
    </div>
</section>

<?php if ($steps): ?>
<section class="section section--soft">
    <div class="container">
        <div class="sx-head reveal">
            <?php if (c('servicos.steps_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('servicos.steps_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('servicos.steps_title')) ?></h2>
            <?php if (c('servicos.steps_text') !== ''): ?><p class="lead"><?= e(c('servicos.steps_text')) ?></p><?php endif; ?>
        </div>
        <div class="steps">
            <?php foreach ($steps as $i => $st): ?>
                <div class="step reveal" data-delay="<?= $i % 3 ?>">
                    <span class="step__n"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    <h3><?= e($st['title']) ?></h3>
                    <p class="muted"><?= e($st['text']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container split">
        <div class="split__content reveal">
            <?php if (c('servicos.feature_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('servicos.feature_eyebrow')) ?></p><?php endif; ?>
            <h2><?= e(c('servicos.feature_title')) ?></h2>
            <?= content_paragraphs(c('servicos.feature_text'), 'muted') ?>
            <?php if (c('servicos.feature_btn') !== ''): ?>
                <div class="btn-group" style="margin-top:28px">
                    <a class="btn btn--dark" href="<?= e(url('/projetos')) ?>"><?= e(c('servicos.feature_btn')) ?> <span class="arrow">→</span></a>
                </div>
            <?php endif; ?>
        </div>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(upload_url(c('servicos.feature_image'))) ?>" alt="<?= e(c('servicos.feature_title')) ?>" loading="lazy">
        </div>
    </div>
</section>

<?php $this->partial('site/partials/contact-band'); ?>
