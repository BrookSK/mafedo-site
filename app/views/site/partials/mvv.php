<?php
// Missão, visão e valores — usado na Home e em "A Mafedo".
$blocks = [
    ['Missão', 'Entregar construções e reformas de alto padrão com excelência técnica, segurança e compromisso com o resultado dos nossos clientes.'],
    ['Visão', 'Ser reconhecida como referência em engenharia pela qualidade do acabamento e pela capacidade de execução de cada obra.'],
    ['Valores', 'Ética, trabalho em equipe, rigor técnico, responsabilidade, prazo e transparência em cada obra.'],
];
?>
<div class="mvv">
    <?php foreach ($blocks as $i => $b): ?>
        <div class="mvv__item reveal" data-delay="<?= $i ?>">
            <span class="mvv__k"><?= e($b[0]) ?></span>
            <p><?= e($b[1]) ?></p>
        </div>
    <?php endforeach; ?>
</div>
