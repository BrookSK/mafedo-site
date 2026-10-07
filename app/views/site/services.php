<section class="section blueprint" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/SERVIÇOS</span><span class="sx-line"></span><span class="sx-label">O que fazemos</span></div>
            <h1>Soluções de engenharia, do projeto à entrega.</h1>
            <p class="lead">Atuação técnica em todas as etapas da sua obra.</p>
        </div>

        <?php if (empty($services)): ?>
            <div class="empty-state reveal">
                <p>Os serviços serão exibidos aqui assim que forem cadastrados no painel administrativo.</p>
                <a class="btn btn--ghost-dark" href="<?= e(url('/contato')) ?>">Fale com a Mafedo</a>
            </div>
        <?php else: ?>
            <div class="svc-list">
                <?php foreach ($services as $i => $s): ?>
                    <a class="svc-row reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                        <span class="svc-row__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <span>
                            <span class="svc-row__title"><?= e($s['title']) ?></span>
                            <span class="svc-row__desc"><?= e(str_excerpt((string) $s['short_description'], 140)) ?></span>
                        </span>
                        <span class="svc-row__go" aria-hidden="true">→</span>
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
