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

<!-- COMO TRABALHAMOS -->
<section class="section section--soft blueprint">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/PROCESSO</span><span class="sx-line"></span><span class="sx-label">Como trabalhamos</span></div>
            <h2>Do planejamento à entrega, com método.</h2>
            <p class="lead">Cada etapa é conduzida com organização, segurança e foco no resultado do cliente.</p>
        </div>
        <div class="steps">
            <?php
            $steps = [
                ['Diagnóstico', 'Entendemos a necessidade, visitamos o local e levantamos o escopo com clareza.'],
                ['Planejamento', 'Definimos projeto, prazo, recursos e orçamento, alinhando expectativas.'],
                ['Execução', 'Colocamos a obra em marcha com equipe especializada e acompanhamento contínuo.'],
                ['Entrega', 'Finalizamos com padrão de qualidade e no prazo combinado, com transparência.'],
            ];
            foreach ($steps as $i => $st): ?>
                <div class="step reveal" data-delay="<?= $i % 3 ?>">
                    <span class="step__n"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    <h3><?= e($st[0]) ?></h3>
                    <p class="muted"><?= e($st[1]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SEGMENTOS DE ATUAÇÃO -->
<section class="section">
    <div class="container split">
        <div class="split__content reveal">
            <div class="sx-index"><span class="sx-num">/SEGMENTOS</span><span class="sx-line"></span><span class="sx-label">Onde atuamos</span></div>
            <h2>Atuação em quatro frentes.</h2>
            <p class="muted">A Mafedo está capacitada para atender diferentes tipos de obra, adaptando a execução a cada contexto.</p>
            <ul class="segment-list">
                <li><span>Corporativo</span><small>Escritórios, sedes e espaços de trabalho</small></li>
                <li><span>Comercial</span><small>Lojas, pontos comerciais e varejo</small></li>
                <li><span>Predial</span><small>Condomínios e edifícios</small></li>
                <li><span>Residencial</span><small>Casas e unidades residenciais</small></li>
            </ul>
        </div>
        <div class="split__media reveal ticked" data-delay="1">
            <img src="<?= e(asset('images/site/services-segments.jpg')) ?>" alt="Reforma predial executada pela Mafedo" loading="lazy">
        </div>
    </div>
</section>

<section class="section cta-band blueprint">
    <div class="container reveal">
        <div class="sx-index" style="justify-content:center; color:var(--orange)"><span class="sx-line" style="max-width:40px"></span><span>Fale com a nossa equipe</span><span class="sx-line" style="max-width:40px"></span></div>
        <h2>Precisa de uma solução sob medida?</h2>
        <div class="btn-group" style="justify-content:center; margin-top:28px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Solicitar contato <span class="arrow">→</span></a>
            <a class="btn btn--outline" href="<?= e(url('/projetos')) ?>">Ver projetos</a>
        </div>
    </div>
</section>
