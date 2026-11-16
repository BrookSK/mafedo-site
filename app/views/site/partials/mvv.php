<?php
// Missão, visão e valores (Painel > Textos e imagens do site > Geral).
$blocks = array_filter([
    'Missão'  => c('geral.mission'),
    'Visão'   => c('geral.vision'),
    'Valores' => c('geral.values'),
], static fn ($t) => trim($t) !== '');
?>
<section class="section section--soft" id="valores">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow"><?= e(c('geral.mvv_eyebrow')) ?></p>
            <h2><?= e(c('geral.mvv_title')) ?></h2>
        </div>
        <div class="mvv">
            <?php $i = 0; foreach ($blocks as $label => $text): ?>
                <div class="mvv__item reveal" data-delay="<?= $i++ ?>">
                    <span class="mvv__k"><?= e($label) ?></span>
                    <p><?= nl2br(e($text)) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
