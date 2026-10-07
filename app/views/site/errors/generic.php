<section class="section" style="padding-top: calc(var(--header-h) + 80px); text-align:center">
    <div class="container">
        <p class="eyebrow mx-auto" style="justify-content:center">Erro <?= (int) ($code ?? 500) ?></p>
        <h1><?= e($message ?? 'Algo deu errado') ?></h1>
        <p class="lead mx-auto"><?= (int)($code ?? 0) === 404
            ? 'A página que você procura não foi encontrada ou foi movida.'
            : 'Ocorreu um erro ao processar sua solicitação. Tente novamente em instantes.' ?></p>
        <div class="btn-group" style="justify-content:center; margin-top:30px">
            <a class="btn" href="<?= e(url('/')) ?>">Voltar ao início</a>
            <a class="btn btn--ghost-dark" href="<?= e(url('/contato')) ?>">Falar com a Mafedo</a>
        </div>
    </div>
</section>
