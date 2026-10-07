<?php
use App\Models\Setting;

$waEnabled = Setting::bool('whatsapp_enabled', false);
$waNumber = preg_replace('/\D+/', '', (string) Setting::get('whatsapp_number', ''));
$waMessage = (string) Setting::get('whatsapp_message', '');
?>
<?php if ($waEnabled && $waNumber !== ''): ?>
    <a class="wa-float"
       href="https://wa.me/<?= e($waNumber) ?><?= $waMessage !== '' ? '?text=' . rawurlencode($waMessage) : '' ?>"
       target="_blank" rel="noopener" aria-label="Falar no WhatsApp">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M19.05 4.91A9.82 9.82 0 0 0 12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.02zM12.05 20.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23a8.19 8.19 0 0 1 8.23 8.24c0 4.54-3.7 8.23-8.24 8.23zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.23.25-.86.85-.86 2.07s.89 2.4 1.01 2.56c.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"/>
        </svg>
    </a>
<?php endif; ?>

<div class="cookie" style="display:none" role="dialog" aria-label="Aviso de cookies">
    <p>Usamos cookies para melhorar sua experiência de navegação. Ao continuar, você concorda com nossa
        <a href="<?= e(url('/politica-de-privacidade')) ?>" style="color:var(--orange)">Política de Privacidade</a>.</p>
    <div class="btn-group">
        <button class="btn btn--ghost-dark" data-cookie="rejected" type="button">Rejeitar</button>
        <button class="btn" data-cookie="accepted" type="button">Aceitar</button>
    </div>
</div>
