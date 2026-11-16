<?php
// Nossos diferenciais. $full = true mostra o texto completo (A Mafedo);
// caso contrário, o resumo (página inicial).
$full = !empty($full);
$diffs = content_items('geral', 'diferenciais_itens');
if (!$diffs) return;
?>
<section class="section<?= $full ? ' section--soft' : '' ?>">
    <div class="container">
        <div class="sx-head reveal">
            <p class="eyebrow"><?= e(c('geral.diff_eyebrow')) ?></p>
            <h2><?= e(c('geral.diff_title')) ?></h2>
        </div>
        <div class="diff-grid diff-grid--light diff-grid--3<?= $full ? ' diff-grid--full' : '' ?>">
            <?php foreach ($diffs as $i => $d):
                $text = $full ? ($d['text'] !== '' ? $d['text'] : $d['summary']) : ($d['summary'] !== '' ? $d['summary'] : $d['text']); ?>
                <div class="diff reveal" data-delay="<?= $i % 3 ?>">
                    <span class="diff__ico"><?= icon($d['icon'] !== '' ? $d['icon'] : 'check') ?></span>
                    <h3><?= e($d['title']) ?></h3>
                    <div class="muted"><?= content_paragraphs($text) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
