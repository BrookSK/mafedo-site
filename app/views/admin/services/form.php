<?php
$isEdit = $service !== null;
$action = $isEdit ? url('admin/servicos/' . $service['id']) : url('admin/servicos');
$val = static fn (string $k, string $d = '') => e($isEdit ? ($service[$k] ?? $d) : old($k, $d));
$err = static fn (string $k) => isset($errors[$k]) ? '<span class="field__error">' . e($errors[$k]) . '</span>' : '';
$hasErr = static fn (string $k) => isset($errors[$k]) ? ' field--error' : '';
?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / <a href="<?= e(url('admin/servicos')) ?>">Serviços</a> / <?= $isEdit ? 'Editar' : 'Novo' ?></div>
<div class="page-head"><h1><?= $isEdit ? 'Editar serviço' : 'Novo serviço' ?></h1></div>

<form method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid cols-2">
        <div class="card" style="grid-column: span 2">
            <div class="card__head">Informações do serviço</div>
            <div class="card__body form-grid">
                <div class="field<?= $hasErr('title') ?>">
                    <label for="title">Título *</label>
                    <input class="input" id="title" name="title" value="<?= $val('title') ?>" required>
                    <?= $err('title') ?>
                </div>
                <div class="field">
                    <label for="slug">Slug (opcional)</label>
                    <input class="input" id="slug" name="slug" value="<?= $val('slug') ?>" placeholder="gerado automaticamente a partir do título">
                    <span class="hint">URL: /servicos/&lt;slug&gt;</span>
                </div>
                <div class="field<?= $hasErr('short_description') ?>">
                    <label for="short_description">Descrição curta</label>
                    <input class="input" id="short_description" name="short_description" value="<?= $val('short_description') ?>" maxlength="320">
                    <?= $err('short_description') ?>
                </div>
                <div class="field">
                    <label for="description">Descrição completa</label>
                    <textarea class="textarea" id="description" name="description" rows="8"><?= $val('description') ?></textarea>
                    <span class="hint">Pode conter quebras de linha. HTML será escapado na exibição.</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card__head">Mídia e aparência</div>
            <div class="card__body form-grid">
                <div class="field">
                    <label for="image">Imagem</label>
                    <?php if ($isEdit && !empty($service['image'])): ?>
                        <img class="thumb" style="width:120px;height:80px" src="<?= e(upload_url($service['image'])) ?>" alt="">
                    <?php endif; ?>
                    <input class="input" type="file" id="image" name="image" accept="image/*">
                    <span class="hint">JPG, PNG, WEBP, AVIF ou SVG. Máx. 8 MB.</span>
                </div>
                <div class="field">
                    <label for="icon">Ícone (nome/classe opcional)</label>
                    <input class="input" id="icon" name="icon" value="<?= $val('icon') ?>" placeholder="ex: estrutura">
                </div>
                <div class="field">
                    <label for="sort_order">Ordem</label>
                    <input class="input" type="number" id="sort_order" name="sort_order" value="<?= e($isEdit ? (string)($service['sort_order'] ?? '0') : old('sort_order','0')) ?>">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card__head">Publicação e SEO</div>
            <div class="card__body form-grid">
                <label class="switch">
                    <input type="checkbox" name="status" value="1" <?= ($isEdit ? (int)$service['status']===1 : true) ? 'checked' : '' ?>>
                    Ativo (visível no site)
                </label>
                <label class="switch">
                    <input type="checkbox" name="featured" value="1" <?= ($isEdit && (int)$service['featured']===1) ? 'checked' : '' ?>>
                    Destacar na Home
                </label>
                <div class="field">
                    <label for="seo_title">SEO título</label>
                    <input class="input" id="seo_title" name="seo_title" value="<?= $val('seo_title') ?>" maxlength="180">
                </div>
                <div class="field">
                    <label for="seo_description">SEO descrição</label>
                    <textarea class="textarea" id="seo_description" name="seo_description" rows="3" maxlength="320"><?= $val('seo_description') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-row" style="margin-top:20px">
        <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar alterações' : 'Criar serviço' ?></button>
        <a class="btn btn--ghost" href="<?= e(url('admin/servicos')) ?>">Cancelar</a>
    </div>
</form>
