<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-head reveal">
            <?php if (c('projetos.head_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('projetos.head_eyebrow')) ?></p><?php endif; ?>
            <h1><?= e(c('projetos.head_title')) ?></h1>
            <?php if (c('projetos.head_text') !== ''): ?><p class="lead"><?= nl2br(e(c('projetos.head_text'))) ?></p><?php endif; ?>
        </div>

        <?php if (!empty($categories) && count($categories) > 1): ?>
            <div class="filters reveal">
                <button class="filter is-active" data-filter="all" type="button"><?= e(c('projetos.filter_all')) ?></button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter" data-filter="<?= e($cat) ?>" type="button"><?= e($cat) ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
            <div class="empty-state reveal">
                <p><?= e(c('projetos.empty_text')) ?></p>
            </div>
        <?php else: ?>
            <div class="proj-editorial">
                <?php foreach ($projects as $i => $p): ?>
                    <a class="proj-item reveal" data-delay="<?= $i % 3 ?>" data-category="<?= e($p['category'] ?? '') ?>"
                       href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                        <img src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                        <span class="proj-item__cap">
                            <?php if (!empty($p['category'])): ?><span class="proj-item__cat"><?= e($p['category']) ?></span><?php endif; ?>
                            <span class="proj-item__title"><?= e($p['title']) ?></span>
                            <?php if (!empty($p['location'])): ?><span class="proj-item__meta"><?= e($p['location']) ?><?= !empty($p['year']) ? ' · ' . e($p['year']) : '' ?></span><?php endif; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php $this->partial('site/partials/contact-band'); ?>
