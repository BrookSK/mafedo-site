<?php
use App\Core\Session;
use App\Models\Setting;

// Textos: Painel > Textos e imagens do site > Contato.
// Telefone, e-mail, endereço e horário: Painel > Configurações > Geral.
$email = (string) Setting::get('contact_email', '');
$phone = (string) Setting::get('contact_phone', '');
$address = (string) Setting::get('contact_address', '');
$hours = (string) Setting::get('business_hours', '');
$waLink = whatsapp_link();
$igLink = instagram_url();

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
            <?php if (c('contato.head_eyebrow') !== ''): ?><p class="eyebrow"><?= e(c('contato.head_eyebrow')) ?></p><?php endif; ?>
            <h1><?= e(c('contato.head_title')) ?></h1>
            <?php if (c('contato.head_text') !== ''): ?><p class="lead"><?= nl2br(e(c('contato.head_text'))) ?></p><?php endif; ?>
        </div>

        <div class="contact-grid">
            <div class="reveal">
                <div class="channels">
                    <?php if ($phone !== ''): ?>
                    <a class="channel" href="tel:<?= e(preg_replace('/\D+/', '', $phone)) ?>">
                        <span class="channel__ico"><?= icon('phone') ?></span>
                        <span class="channel__body">
                            <span class="channel__k"><?= e(c('contato.label_phone')) ?></span>
                            <span class="channel__v"><?= e($phone) ?></span>
                        </span>
                    </a>
                    <?php endif; ?>

                    <a class="channel" href="mailto:<?= e($emailDisplay) ?>">
                        <span class="channel__ico"><?= icon('mail') ?></span>
                        <span class="channel__body">
                            <span class="channel__k"><?= e(c('contato.label_email')) ?></span>
                            <span class="channel__v"><?= e($emailDisplay) ?></span>
                        </span>
                    </a>

                    <div class="channel">
                        <span class="channel__ico"><?= icon('pin') ?></span>
                        <span class="channel__body">
                            <span class="channel__k"><?= e(c('contato.label_address')) ?></span>
                            <span class="channel__v"><?= nl2br(e($addressDisplay)) ?></span>
                        </span>
                    </div>

                    <div class="channel">
                        <span class="channel__ico"><?= icon('clock') ?></span>
                        <span class="channel__body">
                            <span class="channel__k"><?= e(c('contato.label_hours')) ?></span>
                            <span class="channel__v"><?= e($hoursDisplay) ?></span>
                        </span>
                    </div>
                </div>

                <div class="quick-links">
                    <?php if ($waLink !== '' && c('contato.btn_whatsapp') !== ''): ?>
                    <a class="quick-link quick-link--wa" href="<?= e($waLink) ?>" target="_blank" rel="noopener">
                        <?= icon('whatsapp') ?><span><?= e(c('contato.btn_whatsapp')) ?></span>
                    </a>
                    <?php endif; ?>
                    <?php if (c('contato.btn_instagram') !== ''): ?>
                    <a class="quick-link quick-link--ig" href="<?= e($igLink) ?>" target="_blank" rel="noopener">
                        <?= icon('instagram') ?><span><?= e(c('contato.btn_instagram')) ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="reveal" data-delay="1">
                <div class="form-card">
                    <h2 class="form-card__title"><?= e(c('contato.form_title')) ?></h2>
                    <?php if (!empty($sent)): ?>
                        <div class="notice notice--ok" role="status"><?= e(c('contato.form_success')) ?></div>
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
                                <label for="name"><?= e(c('contato.field_name')) ?> *</label>
                                <input id="name" name="name" value="<?= e(old('name')) ?>" autocomplete="name" required>
                                <?= $err('name') ?>
                            </div>
                            <div class="field<?= $hasErr('email') ?>">
                                <label for="email"><?= e(c('contato.field_email')) ?> *</label>
                                <input id="email" type="email" name="email" value="<?= e(old('email')) ?>" autocomplete="email" required>
                                <?= $err('email') ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="field">
                                <label for="phone"><?= e(c('contato.field_phone')) ?></label>
                                <input id="phone" type="tel" name="phone" inputmode="tel" autocomplete="tel" placeholder="(11) 90000-0000" value="<?= e(old('phone')) ?>">
                            </div>
                            <div class="field">
                                <label for="company"><?= e(c('contato.field_company')) ?></label>
                                <input id="company" name="company" value="<?= e(old('company')) ?>">
                            </div>
                        </div>
                        <div class="field">
                            <label for="subject"><?= e(c('contato.field_subject')) ?></label>
                            <input id="subject" name="subject" value="<?= e(old('subject')) ?>">
                        </div>
                        <div class="field<?= $hasErr('message') ?>">
                            <label for="message"><?= e(c('contato.field_message')) ?> *</label>
                            <textarea id="message" name="message" required><?= e(old('message')) ?></textarea>
                            <?= $err('message') ?>
                        </div>
                        <div>
                            <button class="btn btn--block" type="submit"><?= e(c('contato.form_button')) ?> <span class="arrow">→</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
