<?php
// Faixa de contato rápido (Painel > Textos e imagens do site > Geral).
$waLink = whatsapp_link();
?>
<section class="section cta-band" id="contato-rapido">
    <div class="container reveal">
        <?php if (c('geral.cta_eyebrow') !== ''): ?><p class="eyebrow eyebrow--center"><?= e(c('geral.cta_eyebrow')) ?></p><?php endif; ?>
        <h2><?= e(c('geral.cta_title')) ?></h2>
        <?php if (c('geral.cta_text') !== ''): ?><p class="cta-band__sub"><?= e(c('geral.cta_text')) ?></p><?php endif; ?>
        <div class="btn-group cta-band__actions">
            <?php if ($waLink !== '' && c('geral.cta_whatsapp') !== ''): ?>
                <a class="btn btn--wa" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> <?= e(c('geral.cta_whatsapp')) ?></a>
            <?php endif; ?>
            <?php if (c('geral.cta_instagram') !== ''): ?>
                <a class="btn btn--outline" href="<?= e(instagram_url()) ?>" target="_blank" rel="noopener"><?= icon('instagram') ?> <?= e(c('geral.cta_instagram')) ?></a>
            <?php endif; ?>
            <?php if (c('geral.cta_message') !== ''): ?>
                <a class="btn btn--outline" href="<?= e(url('/contato')) ?>"><?= e(c('geral.cta_message')) ?> <span class="arrow">→</span></a>
            <?php endif; ?>
        </div>
    </div>
</section>
