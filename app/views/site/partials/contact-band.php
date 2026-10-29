<?php
// Faixa de contato rápido: leva o visitante direto ao canal, em um clique.
$title = $title ?? 'Vamos conversar sobre o seu próximo projeto?';
$waLink = whatsapp_link();
?>
<section class="section cta-band" id="contato-rapido">
    <div class="container reveal">
        <p class="eyebrow eyebrow--center">Atendimento direto</p>
        <h2><?= e($title) ?></h2>
        <p class="cta-band__sub">Fale com a nossa equipe pelo canal que preferir.</p>
        <div class="btn-group cta-band__actions">
            <?php if ($waLink !== ''): ?>
                <a class="btn btn--wa" href="<?= e($waLink) ?>" target="_blank" rel="noopener"><?= icon('whatsapp') ?> WhatsApp</a>
            <?php endif; ?>
            <a class="btn btn--outline" href="<?= e(instagram_url()) ?>" target="_blank" rel="noopener"><?= icon('instagram') ?> Instagram</a>
            <a class="btn btn--outline" href="<?= e(url('/contato')) ?>">Enviar mensagem <span class="arrow">→</span></a>
        </div>
    </div>
</section>
