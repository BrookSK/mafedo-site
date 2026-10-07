<section class="section blueprint" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/PORTFÓLIO</span><span class="sx-line"></span><span class="sx-label">Projetos</span></div>
            <h1>Obras e projetos da Mafedo.</h1>
            <p class="lead">Uma seleção que representa nossa atuação em construção, reforma e manutenção.</p>
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
            <div class="proj-editorial">
                <?php foreach ($projects as $i => $p): ?>
                    <a class="proj-item reveal" data-delay="<?= $i % 3 ?>" data-category="<?= e($p['category'] ?? '') ?>"
                       href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                        <span class="proj-item__idx">PROJ_<?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
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
