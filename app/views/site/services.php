<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">O que fazemos</p>
            <h1>Serviços</h1>
            <p class="lead">Soluções de engenharia para cada etapa do seu projeto.</p>
        </div>

        <?php if (empty($services)): ?>
            <div class="empty-state reveal">
                <p>Os serviços serão exibidos aqui assim que forem cadastrados no painel administrativo.</p>
                <a class="btn btn--ghost-dark" href="<?= e(url('/contato')) ?>">Fale com a Mafedo</a>
            </div>
        <?php else: ?>
            <div class="services-grid">
                <?php foreach ($services as $i => $s): ?>
                    <a class="service-card reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                        <span class="ico"><?= e(mb_strtoupper(mb_substr($s['title'], 0, 1))) ?></span>
                        <h3><?= e($s['title']) ?></h3>
                        <p class="muted"><?= e(str_excerpt((string) $s['short_description'], 140)) ?></p>
                        <span class="more">Saiba mais →</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section cta-band">
    <div class="container reveal">
        <h2>Precisa de uma solução sob medida?</h2>
        <div class="btn-group" style="justify-content:center; margin-top:28px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Solicitar contato <span class="arrow">→</span></a>
        </div>
    </div>
</section>
