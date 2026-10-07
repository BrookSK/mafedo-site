<?php use App\Models\Setting; ?>

<!-- 01 — HERO -->
<section class="hero">
    <div class="hero__media">
        <?php if (!empty($hero['image'])): ?>
            <img src="<?= e(upload_url($hero['image'])) ?>" alt="" fetchpriority="high">
        <?php else: ?>
            <img src="<?= e(asset('images/site/home-hero.jpg')) ?>" alt="" fetchpriority="high">
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <span class="hero__code"><?= date('Y') ?> · SÃO PAULO · SP</span>
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
    <span class="hero__tech"><span class="dot"></span> Construção · Reforma · Manutenção</span>
    <a href="#intro" class="hero__scroll" aria-label="Rolar para o conteúdo">Role</a>
</section>

<!-- 02 — INTRODUÇÃO INSTITUCIONAL -->
<section class="section blueprint" id="intro">
    <div class="container split">
        <div class="split__content reveal">
            <div class="sx-index"><span class="sx-num">/01</span><span class="sx-line"></span><span class="sx-label">A Mafedo</span></div>
            <h2>Soluções de engenharia com precisão e compromisso.</h2>
            <?php if (!empty($aboutText)): ?>
                <p class="lead"><?= e($aboutText) ?></p>
            <?php endif; ?>
            <div class="btn-group" style="margin-top:28px">
                <a class="btn btn--dark" href="<?= e(url('/sobre')) ?>">Conheça a Mafedo <span class="arrow">→</span></a>
            </div>
        </div>
        <div class="split__media reveal ticked" data-delay="1">
            <img src="<?= e(Setting::get('home_hero_image') ? upload_url((string) Setting::get('home_hero_image')) : asset('images/site/home-intro.jpg')) ?>" alt="Equipe de engenharia da Mafedo em obra" loading="lazy">
        </div>
    </div>
</section>

<!-- 04 — SERVIÇOS (sumário editorial numerado) -->
<?php if (!empty($featuredServices)): ?>
<section class="section section--soft blueprint">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/02</span><span class="sx-line"></span><span class="sx-label">O que fazemos</span></div>
            <h2>Serviços de engenharia, do projeto à entrega.</h2>
        </div>
        <div class="svc-list">
            <?php foreach ($featuredServices as $i => $s): ?>
                <a class="svc-row reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/servicos/' . $s['slug'])) ?>">
                    <span class="svc-row__num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    <span>
                        <span class="svc-row__title"><?= e($s['title']) ?></span>
                        <span class="svc-row__desc"><?= e(str_excerpt((string) $s['short_description'], 120)) ?></span>
                    </span>
                    <span class="svc-row__go" aria-hidden="true">→</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="btn-group" style="margin-top:36px">
            <a class="btn btn--ghost-dark" href="<?= e(url('/servicos')) ?>">Ver todos os serviços</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 05 — PROJETOS EM DESTAQUE (editorial assimétrico) -->
<section class="section">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/03</span><span class="sx-line"></span><span class="sx-label">Portfólio</span></div>
            <h2>Projetos que traduzem capacidade de execução.</h2>
        </div>

        <?php if (empty($featuredProjects)): ?>
            <div class="empty-state reveal">
                <p>Os projetos em destaque aparecerão aqui assim que forem cadastrados no painel.</p>
                <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>">Ver portfólio</a>
            </div>
        <?php else: ?>
            <div class="proj-editorial">
                <?php foreach ($featuredProjects as $i => $p): ?>
                    <a class="proj-item reveal" data-delay="<?= $i % 3 ?>" href="<?= e(url('/projetos/' . $p['slug'])) ?>">
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
            <div class="btn-group" style="margin-top:40px">
                <a class="btn btn--ghost-dark" href="<?= e(url('/projetos')) ?>">Ver todos os projetos</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 06 — DIFERENCIAIS (fundo claro, institucional, com ícones) -->
<section class="section section--soft blueprint">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/04</span><span class="sx-line"></span><span class="sx-label">Por que a Mafedo</span></div>
            <h2>O que sustenta cada entrega.</h2>
        </div>
        <div class="diff-grid diff-grid--light">
            <?php
            // Diferenciais baseados nos valores reais da Mafedo (site institucional).
            // Ícone de linha simples para um tom mais humano/institucional.
            $diffs = [
                ['people', 'Equipe especializada', 'Profissionais capacitados para execução dentro do mais elevado padrão de qualidade.'],
                ['gear', 'Gerenciamento', 'Rotina coordenada com foco em qualidade, alinhamento e redução de custos.'],
                ['clock', 'Prazo', 'Uso eficiente dos recursos para entregar atendendo às expectativas do cliente.'],
                ['shield', 'Ética e confiança', 'Honestidade e compromisso em cada relação com clientes e parceiros.'],
            ];
            $icons = [
                'people' => '<path d="M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm7 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/><path d="M2 20a7 7 0 0 1 14 0M15 13a7 7 0 0 1 7 7"/>',
                'gear'   => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>',
                'clock'  => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
                'shield' => '<path d="M12 3l7 3v5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
            ];
            foreach ($diffs as $i => $d): ?>
                <div class="diff reveal" data-delay="<?= $i % 3 ?>">
                    <span class="diff__ico" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?= $icons[$d[0]] ?></svg>
                    </span>
                    <h3><?= e($d[1]) ?></h3>
                    <p class="muted"><?= e($d[2]) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- 08 — CTA -->
<section class="section cta-band blueprint">
    <div class="container reveal">
        <div class="sx-index" style="justify-content:center; color:var(--orange)"><span class="sx-line" style="max-width:40px"></span><span>Vamos construir juntos</span><span class="sx-line" style="max-width:40px"></span></div>
        <h2>Vamos conversar sobre o seu próximo projeto?</h2>
        <div class="btn-group" style="justify-content:center; margin-top:30px">
            <a class="btn" href="<?= e(url('/contato')) ?>">Solicitar contato <span class="arrow">→</span></a>
            <a class="btn btn--outline" href="<?= e(url('/projetos')) ?>">Ver projetos</a>
        </div>
    </div>
</section>
