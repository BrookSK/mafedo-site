<?php use App\Models\Setting; $company = e(Setting::get('company_name', 'Mafedo Engenharia')); ?>
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head"><p class="eyebrow">Legal</p><h1>Termos de Uso</h1>
            <p class="muted">Última atualização: <?= e($updatedAt) ?></p></div>

        <div class="prose">
            <p>Estes Termos de Uso regulam o acesso e a utilização do site da <?= $company ?>. Ao navegar, você concorda com as condições abaixo. <strong>Este é um modelo inicial e deve ser revisado juridicamente antes da publicação definitiva.</strong></p>

            <h2>1. Uso do site</h2>
            <p>O conteúdo deste site é disponibilizado para fins informativos. O uso deve respeitar a legislação vigente e os princípios de boa-fé.</p>

            <h2>2. Propriedade intelectual</h2>
            <p>Textos, imagens, marcas e demais elementos deste site pertencem à <?= $company ?> ou a seus licenciadores e são protegidos por lei. É vedada a reprodução sem autorização.</p>

            <h2>3. Conteúdo e disponibilidade</h2>
            <p>Empenhamo-nos para manter as informações atualizadas e o site disponível, mas não garantimos ausência de interrupções, erros ou imprecisões.</p>

            <h2>4. Links de terceiros</h2>
            <p>Este site pode conter links para sites externos. Não nos responsabilizamos pelo conteúdo ou pelas práticas de privacidade desses sites.</p>

            <h2>5. Limitação de responsabilidade</h2>
            <p>A <?= $company ?> não se responsabiliza por danos decorrentes do uso ou da impossibilidade de uso deste site, salvo nas hipóteses previstas em lei.</p>

            <h2>6. Alterações</h2>
            <p>Estes termos podem ser modificados a qualquer momento. A versão vigente estará sempre disponível nesta página.</p>

            <h2>7. Contato</h2>
            <p>Em caso de dúvidas sobre estes termos, entre em <a href="<?= e(url('/contato')) ?>">contato</a> conosco.</p>
        </div>
    </div>
</section>
