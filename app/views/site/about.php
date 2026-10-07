<?php use App\Models\Setting; $company = e(Setting::get('company_name', 'Mafedo Engenharia')); ?>

<!-- INTRO + imagem lado a lado (mais limpo e institucional) -->
<section class="section blueprint" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-index reveal"><span class="sx-num">/A MAFEDO</span><span class="sx-line"></span><span class="sx-label">Quem somos</span></div>
        <div class="about-intro">
            <div class="about-intro__text reveal">
                <h1>Engenharia construída sobre técnica, confiança e resultados.</h1>
                <p class="lead">A <?= $company ?> atua nos segmentos corporativo, comercial, predial e residencial — desenvolvendo construções, reformas, manutenção e instalações prediais com padrão de qualidade e compromisso com o prazo.</p>
            </div>
            <figure class="about-intro__media reveal ticked" data-delay="1">
                <img src="<?= e(asset('images/site/about-hero.jpg')) ?>" alt="Residência de alto padrão entregue pela Mafedo" loading="lazy">
            </figure>
        </div>
    </div>
</section>

<!-- ABORDAGEM: texto + imagem de projeto -->
<section class="section section--soft blueprint">
    <div class="container split split--text-first">
        <figure class="split__media reveal ticked">
            <img src="<?= e(asset('images/site/about-approach.jpg')) ?>" alt="Profissional da Mafedo desenvolvendo projeto" loading="lazy">
        </figure>
        <div class="split__content reveal" data-delay="1">
            <div class="sx-index"><span class="sx-num">/01</span><span class="sx-line"></span><span class="sx-label">Nossa abordagem</span></div>
            <h2>Competência técnica aliada à capacidade de execução.</h2>
            <p class="muted">Reunimos equipe especializada e um modelo de gestão que mantém a obra alinhada em qualidade, prazo e custo. Do diagnóstico à entrega, acompanhamos cada etapa de perto, com transparência.</p>
            <ul class="list-check">
                <li>Equipe capacitada para o mais elevado padrão de qualidade</li>
                <li>Gerenciamento com foco em prazo e redução de custos</li>
                <li>Relacionamento transparente com clientes e parceiros</li>
                <li>Segurança e responsabilidade em todas as frentes</li>
            </ul>
            <div class="btn-group" style="margin-top:28px">
                <a class="btn btn--dark" href="<?= e(url('/servicos')) ?>">Nossos serviços <span class="arrow">→</span></a>
            </div>
        </div>
    </div>
</section>

<!-- MISSÃO / VISÃO / VALORES (fundo claro, cards sóbrios) -->
<section class="section">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/02</span><span class="sx-line"></span><span class="sx-label">O que nos guia</span></div>
            <h2>Missão, visão e valores.</h2>
        </div>
        <div class="mvv">
            <?php
            $blocks = [
                ['Missão', 'Entregar soluções de engenharia com excelência técnica, segurança e compromisso com o resultado dos nossos clientes.'],
                ['Visão', 'Ser reconhecida como referência em engenharia pela qualidade e pela capacidade de execução de seus projetos.'],
                ['Valores', 'Ética, trabalho em equipe, rigor técnico, responsabilidade, prazo e transparência em cada obra.'],
            ];
            foreach ($blocks as $i => $b): ?>
                <div class="mvv__item reveal" data-delay="<?= $i ?>">
                    <span class="mvv__k"><?= e($b[0]) ?></span>
                    <p><?= e($b[1]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- SEGMENTOS -->
<section class="section section--soft blueprint">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/03</span><span class="sx-line"></span><span class="sx-label">Onde atuamos</span></div>
            <h2>Quatro segmentos, a mesma exigência de qualidade.</h2>
        </div>
        <ul class="segment-list reveal">
            <li><span>Corporativo</span><small>Escritórios, sedes e espaços de trabalho</small></li>
            <li><span>Comercial</span><small>Lojas, pontos comerciais e varejo</small></li>
            <li><span>Predial</span><small>Condomínios e edifícios</small></li>
            <li><span>Residencial</span><small>Casas e unidades residenciais</small></li>
        </ul>
    </div>
</section>

<!-- CTA -->
<section class="section cta-band">
    <div class="container reveal">
        <div class="sx-index" style="justify-content:center; color:var(--orange)"><span class="sx-line" style="max-width:40px"></span><span>Vamos construir juntos</span><span class="sx-line" style="max-width:40px"></span></div>
        <h2>Conte com a <?= $company ?> no seu próximo projeto.</h2>
        <div class="btn-group" style="justify-content:center; margin-top:28px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Fale com a Mafedo <span class="arrow">→</span></a>
            <a class="btn btn--outline" href="<?= e(url('/projetos')) ?>">Ver projetos</a>
        </div>
    </div>
</section>
