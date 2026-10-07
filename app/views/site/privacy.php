<?php use App\Models\Setting; $company = e(Setting::get('company_name', 'Mafedo Engenharia')); ?>
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head"><p class="eyebrow">Legal</p><h1>Política de Privacidade</h1>
            <p class="muted">Última atualização: <?= e($updatedAt) ?></p></div>

        <div class="prose">
            <p>Esta Política de Privacidade descreve como a <?= $company ?> coleta, utiliza e protege as informações fornecidas pelos usuários ao navegar neste site. <strong>Este é um modelo inicial e deve ser revisado juridicamente antes da publicação definitiva.</strong></p>

            <h2>1. Coleta de dados</h2>
            <p>Coletamos dados que você nos fornece voluntariamente, por exemplo, ao preencher o formulário de contato (nome, e-mail, telefone, empresa e mensagem). Também podemos coletar dados de navegação por meio de cookies.</p>

            <h2>2. Finalidade do uso</h2>
            <ul>
                <li>Responder a solicitações e mensagens enviadas pelo site;</li>
                <li>Enviar informações sobre serviços e projetos, quando solicitado;</li>
                <li>Melhorar a experiência de navegação e o desempenho do site.</li>
            </ul>

            <h2>3. Cookies</h2>
            <p>Utilizamos cookies para melhorar a navegação. Você pode gerenciar as preferências de cookies através das configurações do seu navegador.</p>

            <h2>4. Armazenamento e segurança</h2>
            <p>Adotamos medidas técnicas e organizacionais para proteger os dados contra acesso não autorizado, perda ou alteração indevida.</p>

            <h2>5. Compartilhamento</h2>
            <p>Não comercializamos dados pessoais. O compartilhamento ocorre apenas quando necessário para a prestação dos serviços ou por exigência legal.</p>

            <h2>6. Direitos do titular</h2>
            <p>Nos termos da legislação aplicável (LGPD), você pode solicitar acesso, correção, exclusão ou portabilidade dos seus dados, bem como revogar consentimentos.</p>

            <h2>7. Contato</h2>
            <p>Para exercer seus direitos ou esclarecer dúvidas sobre esta política, entre em <a href="<?= e(url('/contato')) ?>">contato</a> conosco.</p>

            <h2>8. Atualizações</h2>
            <p>Esta política pode ser atualizada periodicamente. Recomendamos a revisão regular desta página.</p>
        </div>
    </div>
</section>
