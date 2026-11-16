<?php
// Nossa trajetória em números (Painel > Textos e imagens do site > Geral).
$numbers = content_items('geral', 'numeros_itens');
if (!$numbers) return;
$image = c('geral.numbers_image');
?>
<section class="section numbers-section">
    <div class="container numbers-split<?= $image === '' ? ' numbers-split--noimg' : '' ?>">
        <div class="numbers-split__content">
            <div class="sx-head reveal">
                <p class="eyebrow"><?= e(c('geral.numbers_eyebrow')) ?></p>
                <?php if (c('geral.numbers_title') !== ''): ?><h2><?= e(c('geral.numbers_title')) ?></h2><?php endif; ?>
            </div>
            <div class="numbers">
                <?php foreach ($numbers as $i => $n): ?>
                    <div class="number reveal" data-delay="<?= $i % 3 ?>">
                        <span class="number__value"><?= e($n['value']) ?></span>
                        <span class="number__body">
                            <span class="number__label"><?= e($n['label']) ?></span>
                            <?php if ($n['text'] !== ''): ?><span class="number__text"><?= e($n['text']) ?></span><?php endif; ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if ($image !== ''): ?>
            <figure class="numbers-split__media reveal" data-delay="1">
                <img src="<?= e(upload_url($image)) ?>" alt="<?= e(c('geral.numbers_title')) ?>" loading="lazy">
            </figure>
        <?php endif; ?>
    </div>
</section>
