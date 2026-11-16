<!-- HERO do projeto -->
<section class="detail-hero">
    <div class="detail-hero__media">
        <img src="<?= e(upload_url($project['main_image'] ?? null)) ?>" alt="<?= e($project['title']) ?>" fetchpriority="high">
    </div>
    <div class="detail-hero__overlay"></div>
    <div class="container">
        <div class="detail-hero__inner">
            <?php if (!empty($project['category'])): ?><p class="eyebrow"><?= e($project['category']) ?></p><?php endif; ?>
            <h1><?= e($project['title']) ?></h1>
            <?php if (!empty($project['short_description'])): ?>
                <p class="lead" style="color:#d6ddec"><?= e($project['short_description']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Metadados -->
        <div class="meta-grid reveal">
            <?php
            $metaItems = [
                'Categoria'    => $project['category'] ?? '',
                'Localização'  => $project['location'] ?? '',
                'Ano'          => $project['year'] ?? '',
                'Cliente'      => $project['client'] ?? '',
                'Segmento'     => $project['segment'] ?? '',
            ];
            foreach ($metaItems as $k => $v): if (trim((string) $v) === '') continue; ?>
                <div><div class="k"><?= e($k) ?></div><div class="v"><?= e($v) ?></div></div>
            <?php endforeach; ?>
        </div>

        <div class="split" style="margin-top:50px; align-items:start">
            <div class="split__content reveal">
                <?php if (!empty($project['description'])): ?>
                    <h2><?= e(c('projetos.about_title')) ?></h2>
                    <div class="muted"><?= content_paragraphs((string) $project['description']) ?></div>
                <?php endif; ?>
            </div>
            <?php if (!empty($characteristics)): ?>
                <div class="split__content reveal" data-delay="1">
                    <h3><?= e(c('projetos.features_title')) ?></h3>
                    <ul class="list-check">
                        <?php foreach ($characteristics as $c): ?><li><?= e($c) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($images)): ?>
<section class="section section--soft">
    <div class="container">
        <div class="section-head reveal"><p class="eyebrow"><?= e(c('projetos.gallery_eyebrow')) ?></p><h2><?= e(c('projetos.gallery_title')) ?></h2></div>
        <div class="gallery">
            <?php foreach ($images as $img): ?>
                <a href="<?= e(upload_url($img['image'])) ?>" data-lightbox data-alt="<?= e($img['alt_text'] ?? $project['title']) ?>" class="reveal">
                    <img src="<?= e(upload_url($img['image'])) ?>" alt="<?= e($img['alt_text'] ?: $project['title']) ?>" loading="lazy">
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="section cta-band">
    <div class="container reveal">
        <h2><?= e(c('projetos.cta_title')) ?></h2>
        <div class="btn-group" style="justify-content:center; margin-top:28px">
            <a class="btn" href="<?= e(url('/contato')) ?>"><?= e(c('projetos.cta_btn1')) ?> <span class="arrow">→</span></a>
            <a class="btn btn--outline" href="<?= e(url('/projetos')) ?>"><?= e(c('projetos.cta_btn2')) ?></a>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section">
    <div class="container">
        <div class="section-head reveal"><h2><?= e(c('projetos.related_title')) ?></h2></div>
        <div class="projects-grid">
            <?php foreach ($related as $i => $p): ?>
                <a class="project-card reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                    <img src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                    <span class="project-card__overlay">
                        <?php if (!empty($p['category'])): ?><span class="project-card__cat"><?= e($p['category']) ?></span><?php endif; ?>
                        <span class="project-card__title"><?= e($p['title']) ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
