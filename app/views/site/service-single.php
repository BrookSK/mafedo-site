<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container split">
        <div class="split__content reveal">
            <p class="eyebrow">Serviço</p>
            <h1><?= e($service['title']) ?></h1>
            <?php if (!empty($service['short_description'])): ?>
                <p class="lead"><?= e($service['short_description']) ?></p>
            <?php endif; ?>
            <?php if (!empty($service['description'])): ?>
                <div style="white-space:pre-wrap; color:var(--muted)"><?= e($service['description']) ?></div>
            <?php endif; ?>
            <div class="btn-group" style="margin-top:28px">
                <a class="btn" href="<?= e(url('/contato')) ?>">Solicitar este serviço <span class="arrow">→</span></a>
            </div>
        </div>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(upload_url($service['image'] ?? null)) ?>" alt="<?= e($service['title']) ?>" loading="lazy">
        </div>
    </div>
</section>

<?php if (!empty($others)): ?>
<section class="section section--soft">
    <div class="container">
        <div class="section-head reveal"><p class="eyebrow">Explore mais</p><h2>Outros serviços</h2></div>
        <div class="services-grid">
            <?php foreach ($others as $i => $s): ?>
                <a class="service-card reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                    <span class="ico"><?= e(mb_strtoupper(mb_substr($s['title'], 0, 1))) ?></span>
                    <h3><?= e($s['title']) ?></h3>
                    <p class="muted"><?= e(str_excerpt((string) $s['short_description'], 110)) ?></p>
                    <span class="more">Saiba mais →</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
