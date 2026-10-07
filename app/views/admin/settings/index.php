<?php
$tab = $tab ?? 'general';
$g = static fn (array $arr, string $k, string $d = '') => e($arr[$k] ?? $d);
$tabs = [
    'general'  => 'Geral',
    'smtp'     => 'SMTP',
    'seo'      => 'SEO',
    'whatsapp' => 'WhatsApp',
    'social'   => 'Redes sociais',
    'home'     => 'Home',
];
?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Configurações</div>
<div class="page-head"><h1>Configurações</h1></div>

<div class="card" style="margin-bottom:20px">
    <div class="card__body" style="display:flex;gap:8px;flex-wrap:wrap;padding:14px 16px">
        <?php foreach ($tabs as $key => $label): ?>
            <a class="btn <?= $tab === $key ? 'btn--dark' : 'btn--ghost' ?> btn--sm" href="<?= e(url('admin/configuracoes?tab=' . $key)) ?>"><?= e($label) ?></a>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($tab === 'general'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/geral')) ?>" class="card" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="card__head">Informações gerais</div>
        <div class="card__body form-grid cols-2">
            <div class="field"><label>Nome da empresa</label><input class="input" name="company_name" value="<?= $g($general,'company_name') ?>"></div>
            <div class="field"><label>E-mail principal</label><input class="input" type="email" name="contact_email" value="<?= $g($general,'contact_email') ?>"></div>
            <div class="field"><label>Telefone</label><input class="input" name="contact_phone" value="<?= $g($general,'contact_phone') ?>"></div>
            <div class="field"><label>Horário de atendimento</label><input class="input" name="business_hours" value="<?= $g($general,'business_hours') ?>" placeholder="Seg a Sex, 8h às 18h"></div>
            <div class="field span-2"><label>Endereço</label><textarea class="textarea" name="contact_address" rows="3"><?= $g($general,'contact_address') ?></textarea></div>
        </div>

        <div class="card__head" style="border-top:1px solid var(--border)">Logotipos</div>
        <div class="card__body form-grid cols-2">
            <?php
            $logoHeader = $general['logo_header'] ?? '';
            $logoFooter = $general['logo_footer'] ?? '';
            ?>
            <div class="field">
                <label>Logo do cabeçalho (header)</label>
                <?php if ($logoHeader !== ''): ?>
                    <div style="background:#01071F;padding:12px;border-radius:6px;margin-bottom:8px;display:inline-block">
                        <img src="<?= e(upload_url($logoHeader)) ?>" alt="Logo do header" style="max-height:48px;display:block">
                    </div>
                    <label class="switch" style="font-weight:400"><input type="checkbox" name="logo_header_remove" value="1"> Remover logo (voltar ao texto)</label>
                <?php endif; ?>
                <input class="input" type="file" name="logo_header" accept="image/png,image/svg+xml,image/webp,image/jpeg">
                <span class="hint">Se enviar uma logo, ela substitui o texto "MAFEDO." no cabeçalho. PNG com fundo transparente é o ideal. Máx. 8 MB.</span>
            </div>
            <div class="field">
                <label>Logo do rodapé (footer)</label>
                <?php if ($logoFooter !== ''): ?>
                    <div style="background:#01071F;padding:12px;border-radius:6px;margin-bottom:8px;display:inline-block">
                        <img src="<?= e(upload_url($logoFooter)) ?>" alt="Logo do footer" style="max-height:56px;display:block">
                    </div>
                    <label class="switch" style="font-weight:400"><input type="checkbox" name="logo_footer_remove" value="1"> Remover logo (voltar ao texto)</label>
                <?php endif; ?>
                <input class="input" type="file" name="logo_footer" accept="image/png,image/svg+xml,image/webp,image/jpeg">
                <span class="hint">Se enviar uma logo, ela substitui o texto "MAFEDO." no rodapé. PNG com fundo transparente é o ideal. Máx. 8 MB.</span>
            </div>
        </div>

        <div class="card__body" style="border-top:1px solid var(--border)"><button class="btn btn--primary">Salvar</button></div>
    </form>

<?php elseif ($tab === 'smtp'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/smtp')) ?>" class="card" style="margin-bottom:20px">
        <?= csrf_field() ?>
        <div class="card__head">Servidor SMTP</div>
        <div class="card__body form-grid cols-2">
            <div class="field"><label>Host</label><input class="input" name="smtp_host" value="<?= $g($smtp,'smtp_host') ?>" placeholder="smtp.seudominio.com"></div>
            <div class="field"><label>Porta</label><input class="input" name="smtp_port" value="<?= $g($smtp,'smtp_port','587') ?>"></div>
            <div class="field"><label>Usuário</label><input class="input" name="smtp_username" value="<?= $g($smtp,'smtp_username') ?>" autocomplete="off"></div>
            <div class="field">
                <label>Senha</label>
                <input class="input" type="password" name="smtp_password" value="" autocomplete="new-password" placeholder="<?= ($smtp['smtp_password'] ?? '') !== '' ? '•••••••• (mantida)' : 'defina a senha' ?>">
                <span class="hint">A senha é criptografada no banco. Deixe em branco para manter a atual.</span>
            </div>
            <div class="field">
                <label>Criptografia</label>
                <select class="select" name="smtp_encryption">
                    <?php foreach (['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'Nenhuma'] as $v => $l): ?>
                        <option value="<?= $v ?>" <?= ($smtp['smtp_encryption'] ?? 'tls') === $v ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Nome do remetente</label><input class="input" name="smtp_from_name" value="<?= $g($smtp,'smtp_from_name') ?>"></div>
            <div class="field"><label>E-mail do remetente</label><input class="input" type="email" name="smtp_from_email" value="<?= $g($smtp,'smtp_from_email') ?>"></div>
            <div class="field"><label>E-mail de destino (recebe os contatos)</label><input class="input" type="email" name="smtp_to_email" value="<?= $g($smtp,'smtp_to_email') ?>"></div>
        </div>
        <div class="card__body" style="border-top:1px solid var(--border)"><button class="btn btn--primary">Salvar SMTP</button></div>
    </form>

    <form method="post" action="<?= e(url('admin/configuracoes/smtp/teste')) ?>" class="card">
        <?= csrf_field() ?>
        <div class="card__head">Enviar e-mail de teste</div>
        <div class="card__body form-grid cols-2">
            <div class="field"><label>Enviar para</label><input class="input" type="email" name="test_email" value="<?= $g($smtp,'smtp_to_email') ?>" placeholder="destino@exemplo.com"></div>
            <div class="field" style="align-self:end"><button class="btn btn--dark">Enviar teste</button></div>
        </div>
    </form>

<?php elseif ($tab === 'seo'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/seo')) ?>" class="card">
        <?= csrf_field() ?>
        <div class="card__head">SEO</div>
        <div class="card__body form-grid cols-2">
            <div class="field span-2"><label>Título do site</label><input class="input" name="seo_site_title" value="<?= $g($seo,'seo_site_title') ?>"></div>
            <div class="field span-2"><label>Meta description padrão</label><textarea class="textarea" name="seo_meta_description" rows="3"><?= $g($seo,'seo_meta_description') ?></textarea></div>
            <div class="field"><label>Keywords</label><input class="input" name="seo_keywords" value="<?= $g($seo,'seo_keywords') ?>"></div>
            <div class="field"><label>Open Graph image (URL)</label><input class="input" name="seo_og_image" value="<?= $g($seo,'seo_og_image') ?>"></div>
            <div class="field"><label>Google Analytics ID</label><input class="input" name="seo_google_analytics" value="<?= $g($seo,'seo_google_analytics') ?>" placeholder="G-XXXXXXX"></div>
            <div class="field"><label>Search Console (verificação)</label><input class="input" name="seo_search_console" value="<?= $g($seo,'seo_search_console') ?>"></div>
        </div>
        <div class="card__body" style="border-top:1px solid var(--border)"><button class="btn btn--primary">Salvar</button></div>
    </form>

<?php elseif ($tab === 'whatsapp'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/whatsapp')) ?>" class="card">
        <?= csrf_field() ?>
        <div class="card__head">WhatsApp</div>
        <div class="card__body form-grid cols-2">
            <div class="field"><label>Número (com DDI e DDD)</label><input class="input" name="whatsapp_number" value="<?= $g($whatsapp,'whatsapp_number') ?>" placeholder="5511999999999"></div>
            <div class="field"><label>Mensagem padrão</label><input class="input" name="whatsapp_message" value="<?= $g($whatsapp,'whatsapp_message') ?>"></div>
            <label class="switch span-2">
                <input type="checkbox" name="whatsapp_enabled" value="1" <?= ($whatsapp['whatsapp_enabled'] ?? '0') === '1' ? 'checked' : '' ?>>
                Exibir botão flutuante de WhatsApp no site
            </label>
        </div>
        <div class="card__body" style="border-top:1px solid var(--border)"><button class="btn btn--primary">Salvar</button></div>
    </form>

<?php elseif ($tab === 'social'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/redes')) ?>" class="card">
        <?= csrf_field() ?>
        <div class="card__head">Redes sociais</div>
        <div class="card__body form-grid cols-2">
            <div class="field"><label>Instagram</label><input class="input" name="social_instagram" value="<?= $g($social,'social_instagram') ?>"></div>
            <div class="field"><label>Facebook</label><input class="input" name="social_facebook" value="<?= $g($social,'social_facebook') ?>"></div>
            <div class="field"><label>LinkedIn</label><input class="input" name="social_linkedin" value="<?= $g($social,'social_linkedin') ?>"></div>
            <div class="field"><label>YouTube</label><input class="input" name="social_youtube" value="<?= $g($social,'social_youtube') ?>"></div>
            <span class="hint span-2">Apenas os campos preenchidos aparecem no site.</span>
        </div>
        <div class="card__body" style="border-top:1px solid var(--border)"><button class="btn btn--primary">Salvar</button></div>
    </form>

<?php elseif ($tab === 'home'): ?>
    <form method="post" action="<?= e(url('admin/configuracoes/geral')) ?>" class="card">
        <?= csrf_field() ?>
        <div class="card__head">Conteúdo da Home</div>
        <div class="card__body">
            <p style="color:var(--text-muted)">O conteúdo editável da Home (hero e introdução) também pode ser ajustado aqui futuramente. Os campos estão preparados no banco (grupo <code>home</code>).</p>
            <div class="chips">
                <span class="chip">home_hero_title: <?= e(str_excerpt($home['home_hero_title'] ?? '', 40)) ?></span>
                <span class="chip">home_hero_subtitle: <?= e(str_excerpt($home['home_hero_subtitle'] ?? '', 40)) ?></span>
            </div>
        </div>
    </form>
<?php endif; ?>
