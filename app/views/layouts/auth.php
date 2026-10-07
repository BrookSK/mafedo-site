<?php
use App\Core\Session;
$title = $title ?? 'Entrar';
$success = Session::flash('success');
$error = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title) ?> · Mafedo Engenharia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-card">
        <?php if ($success): ?><div class="alert alert--success"><?= e($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert--error"><?= e($error) ?></div><?php endif; ?>
        <?= $this->content() ?>
    </div>
</div>
</body>
</html>
