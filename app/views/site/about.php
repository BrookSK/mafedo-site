<?php use App\Models\Setting; $company = e(Setting::get('company_name', 'Mafedo Engenharia')); ?>

<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">A Mafedo</p>
            <h1>Engenharia construída sobre técnica, confiança e resultados.</h1>
            <p class="lead">A <?= $company ?> atua com soluções de engenharia pensadas para cada contexto, unindo competência técnica à capacidade de execução.</p>
        </div>
    </div>
</section>

<section class="section section--soft" style="padding-top:0; background:transparent">
    <div class="container split">
        <div class="split__media reveal">
            <img src="<?= e(asset('images/placeholder.svg')) ?>" alt="Atuação da Mafedo" loading="lazy">
        </div>
        <div class="split__content reveal" data-delay="1">
            <p class="eyebrow">Nosso propósito</p>
            <h2>Transformar projetos em obras sólidas.</h2>
            <p class="muted">O conteúdo institucional desta página é gerenciável e será complementado com as informações oficiais da Mafedo. Os textos atuais são um ponto de partida profissional e devem ser revisados com os dados reais da empresa.</p>
            <ul class="list-check">
                <li>Atuação técnica em todas as fases do projeto</li>
                <li>Compromisso com prazos e segurança</li>
                <li>Relacionamento transparente com clientes e parceiros</li>
            </ul>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="feature-grid">
            <?php
            $blocks = [
                ['Missão', 'Entregar soluções de engenharia com excelência técnica, segurança e compromisso com o resultado dos nossos clientes.'],
                ['Visão', 'Ser reconhecida como referência em engenharia pela qualidade e capacidade de execução de seus projetos.'],
                ['Valores', 'Ética, segurança, rigor técnico, responsabilidade e transparência em cada obra.'],
            ];
            foreach ($blocks as $i => $b): ?>
                <div class="feature reveal" data-delay="<?= $i ?>">
                    <span class="n">0<?= $i + 1 ?></span>
                    <h3><?= e($b[0]) ?></h3>
                    <p class="muted"><?= e($b[1]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section cta-band">
    <div class="container reveal">
        <h2>Conte com a <?= $company ?> no seu próximo projeto.</h2>
        <div class="btn-group" style="justify-content:center; margin-top:28px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Fale com a Mafedo <span class="arrow">→</span></a>
        </div>
    </div>
</section>
