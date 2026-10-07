<?php
use App\Core\Session;
use App\Models\Setting;

$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$hours = (string) Setting::get('business_hours', '');
$waNumber = preg_replace('/\D+/', '', (string) Setting::get('whatsapp_number', ''));

$err = static fn (string $k) => isset($errors[$k]) ? '<span class="field-error">' . e($errors[$k]) . '</span>' : '';
$flashError = Session::flash('error');
?>
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="section-head reveal">
            <p class="eyebrow">Contato</p>
            <h1>Vamos conversar sobre o seu projeto.</h1>
            <p class="lead">Preencha o formulário ou utilize nossos canais de atendimento. Retornaremos o mais breve possível.</p>
        </div>

        <div class="contact-grid">
            <!-- Informações -->
            <div class="reveal">
                <h3>Canais de atendimento</h3>
                <div class="info-list">
                    <?php if ($phone !== ''): ?>
                        <div class="item"><div><div class="k">Telefone</div><a href="tel:<?= e(preg_replace('/\D+/','',$phone)) ?>"><?= e($phone) ?></a></div></div>
                    <?php endif; ?>
                    <?php if ($waNumber !== ''): ?>
                        <div class="item"><div><div class="k">WhatsApp</div><a href="https://wa.me/<?= e($waNumber) ?>" target="_blank" rel="noopener">Conversar agora</a></div></div>
                    <?php endif; ?>
                    <?php if ($email !== ''): ?>
                        <div class="item"><div><div class="k">E-mail</div><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></div></div>
                    <?php endif; ?>
                    <?php if ($address !== ''): ?>
                        <div class="item"><div><div class="k">Endereço</div><span><?= nl2br(e($address)) ?></span></div></div>
                    <?php endif; ?>
                    <?php if ($hours !== ''): ?>
                        <div class="item"><div><div class="k">Atendimento</div><span><?= e($hours) ?></span></div></div>
                    <?php endif; ?>
                    <?php if ($phone === '' && $email === '' && $address === '' && $waNumber === ''): ?>
                        <p class="muted">Os canais de contato aparecerão aqui assim que forem configurados no painel.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulário -->
            <div class="reveal" data-delay="1">
                <?php if (!empty($sent)): ?>
                    <div class="notice notice--ok" role="status">Mensagem enviada com sucesso! Em breve entraremos em contato.</div>
                <?php elseif ($flashError): ?>
                    <div class="notice notice--err" role="alert"><?= e($flashError) ?></div>
                <?php endif; ?>

                <form class="form" method="post" action="<?= e(url('/contato')) ?>" novalidate style="margin-top:18px">
                    <?= csrf_field() ?>
                    <!-- Honeypot anti-spam (não preencher) -->
                    <div class="hp" aria-hidden="true">
                        <label>Não preencha<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="row">
                        <div>
                            <label for="name">Nome *</label>
                            <input id="name" name="name" value="<?= e(old('name')) ?>" required>
                            <?= $err('name') ?>
                        </div>
                        <div>
                            <label for="email">E-mail *</label>
                            <input id="email" type="email" name="email" value="<?= e(old('email')) ?>" required>
                            <?= $err('email') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <label for="phone">Telefone</label>
                            <input id="phone" name="phone" value="<?= e(old('phone')) ?>">
                            <?= $err('phone') ?>
                        </div>
                        <div>
                            <label for="company">Empresa</label>
                            <input id="company" name="company" value="<?= e(old('company')) ?>">
                        </div>
                    </div>
                    <div>
                        <label for="subject">Assunto</label>
                        <input id="subject" name="subject" value="<?= e(old('subject')) ?>">
                    </div>
                    <div>
                        <label for="message">Mensagem *</label>
                        <textarea id="message" name="message" required><?= e(old('message')) ?></textarea>
                        <?= $err('message') ?>
                    </div>
                    <div>
                        <button class="btn" type="submit">Enviar mensagem <span class="arrow">→</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
