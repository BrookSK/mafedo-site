<?php use App\Models\Setting; ?>

<!-- 01 — HERO -->
<section class="hero">
    <div class="hero__media">
        <?php if (!empty($hero['image'])): ?>
            <img src="<?= e(upload_url($hero['image'])) ?>" alt="" fetchpriority="high">
        <?php else: ?>
            <img src="<?= e(asset('images/projetos/hero-torres.jpg')) ?>" alt="" fetchpriority="high">
        <?php endif; ?>
    </div>
    <div class="hero__overlay"></div>
    <span class="hero__code">MAFEDO · ENGENHARIA · SP</span>
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
            <img src="<?= e(Setting::get('home_hero_image') ? upload_url((string) Setting::get('home_hero_image')) : asset('images/projetos/comercial.jpg')) ?>" alt="Atuação da Mafedo Engenharia" loading="lazy">
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

<!-- 06 — DIFERENCIAIS (números display) -->
<section class="section section--navy blueprint">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/04</span><span class="sx-line"></span><span class="sx-label">Por que a Mafedo</span></div>
            <h2>O que sustenta cada entrega.</h2>
        </div>
        <div class="diff-grid">
            <?php
            // Diferenciais baseados nos valores reais da Mafedo (site institucional).
            $diffs = [
                ['Equipe especializada', 'Profissionais capacitados para execução dentro do mais elevado padrão de qualidade.'],
                ['Gerenciamento', 'Rotina coordenada com foco em qualidade, alinhamento e redução de custos.'],
                ['Prazo', 'Uso eficiente dos recursos para entregar atendendo às expectativas do cliente.'],
                ['Ética e confiança', 'Honestidade e compromisso em cada relação com clientes e parceiros.'],
            ];
            foreach ($diffs as $i => $d): ?>
                <div class="diff reveal" data-delay="<?= $i % 3 ?>">
                    <span class="diff__n"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                    <h3><?= e($d[0]) ?></h3>
                    <p class="muted"><?= e($d[1]) ?></p>
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
