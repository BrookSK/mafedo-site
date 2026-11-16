<?php
// Nossa trajetória em números (Painel > Textos e imagens do site > Geral).
$numbers = content_items('geral', 'numeros_itens');
if (!$numbers) return;
?>
<section class="section numbers-section">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow"><?= e(c('geral.numbers_eyebrow')) ?></p>
            <?php if (c('geral.numbers_title') !== ''): ?><h2><?= e(c('geral.numbers_title')) ?></h2><?php endif; ?>
        </div>
        <div class="numbers">
            <?php foreach ($numbers as $i => $n): ?>
                <div class="number reveal" data-delay="<?= $i % 3 ?>">
                    <span class="number__value"><?= e($n['value']) ?></span>
                    <span class="number__label"><?= e($n['label']) ?></span>
                    <?php if ($n['text'] !== ''): ?><p class="number__text"><?= e($n['text']) ?></p><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
