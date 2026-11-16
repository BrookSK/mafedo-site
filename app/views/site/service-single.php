<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container split">
        <div class="split__content reveal">
            <?php if (c('projetos.service_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('projetos.service_eyebrow')) ?></p><?php endif; ?>
            <h1><?= e($service['title']) ?></h1>
            <?php if (!empty($service['short_description'])): ?>
                <p class="lead"><?= e($service['short_description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($service['description'])): ?>
                <div class="muted"><?= content_paragraphs((string) $service['description']) ?></div>
            <?php endif; ?>
            <div class="btn-group" style="margin-top:28px">
                <?php if (whatsapp_link() !== ''): ?>
                    <a class="btn" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> <?= e(c('projetos.service_btn')) ?></a>
                <?php else: ?>
                    <a class="btn" href="<?= e(url('/contato')) ?>"><?= e(c('projetos.service_btn')) ?> <span class="arrow">→</span></a>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!empty($service['image'])): ?>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(upload_url($service['image'])) ?>" alt="<?= e($service['title']) ?>" loading="lazy">
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($others)): ?>
<section class="section section--soft">
    <div class="container">
        <div class="sx-head reveal"><h2><?= e(c('projetos.service_others')) ?></h2></div>
        <div class="svc-list">
            <?php foreach ($others as $i => $s): ?>
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

<?php $this->partial('site/partials/contact-band'); ?>
