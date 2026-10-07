<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Portfólio</p>
            <h1>Projetos</h1>
            <p class="lead">Conheça obras e projetos que representam a atuação da Mafedo Engenharia.</p>
        </div>

        <?php if (!empty($categories)): ?>
            <div class="filters reveal">
                <button class="filter is-active" data-filter="all" type="button">Todos</button>
                <?php foreach ($categories as $cat): ?>
                    <button class="filter" data-filter="<?= e($cat) ?>" type="button"><?= e($cat) ?></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($projects)): ?>
            <div class="empty-state reveal">
                <p>O portfólio será exibido aqui assim que os projetos forem cadastrados no painel.</p>
                <a class="btn btn--ghost-dark" href="<?= e(url('/contato')) ?>">Fale com a Mafedo</a>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($projects as $i => $p): ?>
                    <a class="project-card reveal <?= (int) $p['featured'] === 1 ? 'is-featured' : '' ?>"
                       data-delay="<?= $i % 3 ?>" data-category="<?= e($p['category'] ?? '') ?>"
                       href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                        <img src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                        <span class="project-card__overlay">
                            <?php if (!empty($p['category'])): ?><span class="project-card__cat"><?= e($p['category']) ?></span><?php endif; ?>
                            <span class="project-card__title"><?= e($p['title']) ?></span>
                            <?php if (!empty($p['location'])): ?><span class="project-card__meta"><?= e($p['location']) ?><?= !empty($p['year']) ? ' · ' . e($p['year']) : '' ?></span><?php endif; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
