<?php use App\Models\Setting; ?>

<!-- 01 — HERO -->
<section class="hero">
    <div class="hero__media">
        <?php if (!empty($hero['image'])): ?>
            <img src="<?= e(upload_url($hero['image'])) ?>" alt="" fetchpriority="high">
        <?php else: ?>
            <img src="<?= e(asset('images/placeholder.svg')) ?>" alt="">
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__inner">
            <p class="eyebrow"><?= e($hero['eyebrow']) ?></p>
            <h1><?= e($hero['title']) ?></h1>
            <?php if (!empty($hero['subtitle'])): ?><p class="lead"><?= e($hero['subtitle']) ?></p><?php endif; ?>
            <div class="btn-group">
                <a class="btn" href="<?= e(url('/projetos')) ?>">Conheça nossos projetos <span class="arrow">→</span></a>
                <a class="btn btn--outline" href="<?= e(url('/contato')) ?>">Fale conosco</a>
            </div>
        </div>
    </div>
    <a href="#intro" class="hero__scroll" aria-label="Rolar para o conteúdo">Role</a>
</section>

<!-- 02 — INTRODUÇÃO INSTITUCIONAL -->
<section class="section" id="intro">
    <div class="container split">
        <div class="split__content reveal">
            <p class="eyebrow">Quem somos</p>
            <h2>Soluções de engenharia com precisão e compromisso.</h2>
            <?php if (!empty($aboutText)): ?>
                <p class="lead"><?= e($aboutText) ?></p>
            <?php endif; ?>
            <div class="btn-group" style="margin-top:28px">
                <a class="btn btn--dark" href="<?= e(url('/sobre')) ?>">Conheça a Mafedo <span class="arrow">→</span></a>
            </div>
        </div>
        <div class="split__media reveal" data-delay="1">
            <img src="<?= e(upload_url(Setting::get('home_hero_image') ?: null)) ?>" alt="Atuação da Mafedo Engenharia" loading="lazy">
        </div>
    </div>
</section>

<!-- 04 — SERVIÇOS -->
<?php if (!empty($featuredServices)): ?>
<section class="section section--soft">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">O que fazemos</p>
            <h2>Serviços</h2>
            <p class="lead">Atuação técnica em todas as etapas do seu projeto.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($featuredServices as $i => $s): ?>
                <a class="service-card reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                    <span class="ico"><?= e(mb_strtoupper(mb_substr($s['title'], 0, 1))) ?></span>
                    <h3><?= e($s['title']) ?></h3>
                    <p class="muted"><?= e(str_excerpt((string) $s['short_description'], 120)) ?></p>
                    <span class="more">Saiba mais →</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="btn-group" style="margin-top:40px">
            <a class="btn btn--ghost-dark" href="<?= e(url('/servicos')) ?>">Ver todos os serviços</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 05 — PROJETOS EM DESTAQUE -->
<section class="section">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Portfólio</p>
            <h2>Projetos em destaque</h2>
            <p class="lead">Obras que traduzem a capacidade de execução da Mafedo.</p>
        </div>

        <?php if (empty($featuredProjects)): ?>
            <div class="empty-state reveal">
                <p>Os projetos em destaque aparecerão aqui assim que forem cadastrados no painel.</p>
                <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>">Ver portfólio</a>
            </div>
        <?php else: ?>
            <div class="projects-grid">
                <?php foreach ($featuredProjects as $i => $p): ?>
                    <a class="project-card reveal <?= (int) $p['featured'] === 1 ? 'is-featured' : '' ?>" data-delay="<?= $i % 3 ?>" href="<?= e(url('/projetos/' . $p['slug'])) ?>">
                        <img src="<?= e(upload_url($p['main_image'] ?? null)) ?>" alt="<?= e($p['title']) ?>" loading="lazy">
                        <span class="project-card__overlay">
                            <?php if (!empty($p['category'])): ?><span class="project-card__cat"><?= e($p['category']) ?></span><?php endif; ?>
                            <span class="project-card__title"><?= e($p['title']) ?></span>
                            <?php if (!empty($p['location'])): ?><span class="project-card__meta"><?= e($p['location']) ?><?= !empty($p['year']) ? ' · ' . e($p['year']) : '' ?></span><?php endif; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="btn-group" style="margin-top:40px">
                <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>">Ver todos os projetos</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 06 — DIFERENCIAIS -->
<section class="section section--navy">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Por que a Mafedo</p>
            <h2>Diferenciais que sustentam cada entrega.</h2>
        </div>
        <div class="feature-grid">
            <?php
            $diffs = [
                ['Rigor técnico', 'Projetos conduzidos com critério de engenharia e atenção a cada detalhe.'],
                ['Segurança', 'Compromisso com normas e procedimentos que protegem pessoas e obras.'],
                ['Capacidade de execução', 'Estrutura e experiência para transformar planejamento em resultado.'],
                ['Transparência', 'Comunicação clara em todas as etapas do projeto.'],
            ];
            foreach ($diffs as $i => $d): ?>
                <div class="feature reveal" data-delay="<?= $i % 3 ?>">
                    <span class="n">0<?= $i + 1 ?></span>
                    <h3><?= e($d[0]) ?></h3>
                    <p class="muted"><?= e($d[1]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 08 — CTA -->
<section class="section cta-band">
    <div class="container reveal">
        <p class="eyebrow mx-auto" style="justify-content:center">Vamos construir juntos</p>
        <h2>Vamos conversar sobre o seu próximo projeto?</h2>
        <div class="btn-group" style="justify-content:center; margin-top:30px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Solicitar contato <span class="arrow">→</span></a>
            <a class="btn btn--outline" href="<?= e(url('/projetos')) ?>">Ver projetos</a>
        </div>
    </div>
</section>
