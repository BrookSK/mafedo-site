<?php
use App\Core\Session;
use App\Models\Setting;

$company = (string) Setting::get('company_name', 'Mafedo Engenharia');
$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$hours = (string) Setting::get('business_hours', '');
$waNumber = preg_replace('/\D+/', '', (string) Setting::get('whatsapp_number', ''));
$waMsg = (string) Setting::get('whatsapp_message', 'Olá! Gostaria de falar sobre um projeto.');

// Fallbacks: canais sempre visíveis, mesmo sem cadastro no painel.
$phoneDisplay = $phone !== '' ? $phone : 'Em breve';
$emailDisplay = $email !== '' ? $email : 'contato@mafedo.com.br';
$addressDisplay = $address !== '' ? $address : 'São Paulo, SP';
$hoursDisplay = $hours !== '' ? $hours : 'Seg. a Sex., 8h às 18h';

$err = static fn (string $k) => isset($errors[$k]) ? '<span class="field-error">' . e($errors[$k]) . '</span>' : '';
$hasErr = static fn (string $k) => isset($errors[$k]) ? ' has-error' : '';
$flashError = Session::flash('error');
?>
<section class="section" style="padding-top: calc(var(--header-h) + 70px)">
    <div class="container">
        <div class="sx-head reveal">
            <div class="sx-index"><span class="sx-num">/CONTATO</span><span class="sx-line"></span><span class="sx-label">Fale com a Mafedo</span></div>
            <h1>Vamos conversar sobre o seu projeto.</h1>
            <p class="lead">Preencha o formulário ou use um dos nossos canais de atendimento. Retornamos o mais breve possível.</p>
        </div>

        <div class="contact-grid">
            <!-- Canais de atendimento (cards sempre visíveis) -->
            <div class="reveal">
                <div class="channels">
                    <?php if ($waNumber !== ''): ?>
                    <a class="channel channel--wa" href="https://wa.me/<?= e($waNumber) ?><?= $waMsg !== '' ? '?text=' . rawurlencode($waMsg) : '' ?>" target="_blank" rel="noopener">
                        <span class="channel__ico"><?= icon('whatsapp') ?></span>
                        <span class="channel__body">
                            <span class="channel__k">WhatsApp</span>
                            <span class="channel__v">Conversar agora</span>
                        </span>
                        <span class="channel__go"><?= icon('arrow') ?></span>
                    </a>
                    <?php endif; ?>

                    <a class="channel" <?= $phone !== '' ? 'href="tel:' . e(preg_replace('/\D+/', '', $phone)) . '"' : '' ?>>
                        <span class="channel__ico"><?= icon('phone') ?></span>
                        <span class="channel__body">
                            <span class="channel__k">Telefone</span>
                            <span class="channel__v"><?= e($phoneDisplay) ?></span>
                        </span>
                    </a>

                    <a class="channel" href="mailto:<?= e($emailDisplay) ?>">
                        <span class="channel__ico"><?= icon('mail') ?></span>
                        <span class="channel__body">
                            <span class="channel__k">E-mail</span>
                            <span class="channel__v"><?= e($emailDisplay) ?></span>
                        </span>
                    </a>

                    <div class="channel">
                        <span class="channel__ico"><?= icon('pin') ?></span>
                        <span class="channel__body">
                            <span class="channel__k">Endereço</span>
                            <span class="channel__v"><?= nl2br(e($addressDisplay)) ?></span>
                        </span>
                    </div>

                    <div class="channel">
                        <span class="channel__ico"><?= icon('clock') ?></span>
                        <span class="channel__body">
                            <span class="channel__k">Atendimento</span>
                            <span class="channel__v"><?= e($hoursDisplay) ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulário -->
            <div class="reveal" data-delay="1">
                <div class="form-card ticked">
                    <h2 class="form-card__title">Envie uma mensagem</h2>
                    <?php if (!empty($sent)): ?>
                        <div class="notice notice--ok" role="status">Mensagem enviada com sucesso! Em breve entraremos em contato.</div>
                    <?php elseif ($flashError): ?>
                        <div class="notice notice--err" role="alert"><?= e($flashError) ?></div>
                    <?php endif; ?>

                    <form class="form" method="post" action="<?= e(url('/contato')) ?>" novalidate>
                        <?= csrf_field() ?>
                        <div class="hp" aria-hidden="true">
                            <label>Não preencha<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="row">
                            <div class="field<?= $hasErr('name') ?>">
                                <label for="name">Nome *</label>
                                <input id="name" name="name" value="<?= e(old('name')) ?>" required>
                                <?= $err('name') ?>
                            </div>
                            <div class="field<?= $hasErr('email') ?>">
                                <label for="email">E-mail *</label>
                                <input id="email" type="email" name="email" value="<?= e(old('email')) ?>" required>
                                <?= $err('email') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <label for="phone">Telefone</label>
                                <input id="phone" name="phone" value="<?= e(old('phone')) ?>">
                            </div>
                            <div class="field">
                                <label for="company">Empresa</label>
                                <input id="company" name="company" value="<?= e(old('company')) ?>">
                            </div>
                        </div>
                        <div class="field">
                            <label for="subject">Assunto</label>
                            <input id="subject" name="subject" value="<?= e(old('subject')) ?>">
                        </div>
                        <div class="field<?= $hasErr('message') ?>">
                            <label for="message">Mensagem *</label>
                            <textarea id="message" name="message" required><?= e(old('message')) ?></textarea>
                            <?= $err('message') ?>
                        </div>
                        <div>
                            <button class="btn btn--block" type="submit">Enviar mensagem <span class="arrow">→</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
