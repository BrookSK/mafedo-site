<?php
$isEdit = $project !== null;
$action = $isEdit ? url('admin/projetos/' . $project['id']) : url('admin/projetos');
$val = static fn (string $k, string $d = '') => e($isEdit ? ($project[$k] ?? $d) : old($k, $d));
$err = static fn (string $k) => isset($errors[$k]) ? '<span class="field__error">' . e($errors[$k]) . '</span>' : '';
$hasErr = static fn (string $k) => isset($errors[$k]) ? ' field--error' : '';
?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / <a href="<?= e(url('admin/projetos')) ?>">Projetos</a> / <?= $isEdit ? 'Editar' : 'Novo' ?></div>
<div class="page-head"><h1><?= $isEdit ? 'Editar projeto' : 'Novo projeto' ?></h1></div>

<form method="post" action="<?= e($action) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid cols-2">
        <div class="card" style="grid-column: span 2">
            <div class="card__head">Informações principais</div>
            <div class="card__body form-grid cols-2">
                <div class="field span-2<?= $hasErr('title') ?>">
                    <label for="title">Título *</label>
                    <input class="input" id="title" name="title" value="<?= $val('title') ?>" required>
                    <?= $err('title') ?>
                </div>
                <div class="field">
                    <label for="category">Categoria</label>
                    <input class="input" id="category" name="category" value="<?= $val('category') ?>" placeholder="ex: Residencial, Industrial">
                </div>
                <div class="field">
                    <label for="location">Localização</label>
                    <input class="input" id="location" name="location" value="<?= $val('location') ?>" placeholder="ex: São Paulo, SP">
                </div>
                <div class="field">
                    <label for="year">Ano</label>
                    <input class="input" id="year" name="year" value="<?= $val('year') ?>" placeholder="ex: 2025">
                </div>
                <div class="field">
                    <label for="segment">Área / Segmento</label>
                    <input class="input" id="segment" name="segment" value="<?= $val('segment') ?>">
                </div>
                <div class="field">
                    <label for="client">Cliente (se divulgável)</label>
                    <input class="input" id="client" name="client" value="<?= $val('client') ?>">
                </div>
                <div class="field">
                    <label for="slug">Slug (opcional)</label>
                    <input class="input" id="slug" name="slug" value="<?= $val('slug') ?>" placeholder="gerado automaticamente">
                </div>
                <div class="field span-2">
                    <label for="short_description">Descrição curta</label>
                    <input class="input" id="short_description" name="short_description" value="<?= $val('short_description') ?>" maxlength="320">
                </div>
                <div class="field span-2">
                    <label for="description">Descrição completa</label>
                    <textarea class="textarea" id="description" name="description" rows="7"><?= $val('description') ?></textarea>
                </div>
                <div class="field span-2">
                    <label for="characteristics">Características (uma por linha)</label>
                    <textarea class="textarea" id="characteristics" name="characteristics" rows="5" placeholder="Área construída: 1.200 m²&#10;Prazo: 10 meses"><?= $val('characteristics') ?></textarea>
                    <span class="hint">Cada linha vira um item na página do projeto.</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card__head">Imagem principal</div>
            <div class="card__body form-grid">
                <?php if ($isEdit && !empty($project['main_image'])): ?>
                    <img class="thumb" style="width:100%;height:160px" src="<?= e(upload_url($project['main_image'])) ?>" alt="">
                <?php endif; ?>
                <div class="field">
                    <input class="input" type="file" name="main_image" accept="image/*">
                    <span class="hint">Imagem de destaque do projeto. Máx. 8 MB.</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card__head">Publicação e SEO</div>
            <div class="card__body form-grid">
                <label class="switch">
                    <input type="checkbox" name="status" value="1" <?= ($isEdit ? (int)$project['status']===1 : true) ? 'checked' : '' ?>>
                    Publicado
                </label>
                <label class="switch">
                    <input type="checkbox" name="featured" value="1" <?= ($isEdit && (int)$project['featured']===1) ? 'checked' : '' ?>>
                    Destacar na Home
                </label>
                <div class="field">
                    <label for="sort_order">Ordem</label>
                    <input class="input" type="number" id="sort_order" name="sort_order" value="<?= e($isEdit ? (string)($project['sort_order'] ?? '0') : old('sort_order','0')) ?>">
                </div>
                <div class="field">
                    <label for="seo_title">SEO título</label>
                    <input class="input" id="seo_title" name="seo_title" value="<?= $val('seo_title') ?>">
                </div>
                <div class="field">
                    <label for="seo_description">SEO descrição</label>
                    <textarea class="textarea" id="seo_description" name="seo_description" rows="3"><?= $val('seo_description') ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="btn-row" style="margin-top:20px">
        <button type="submit" class="btn btn--primary"><?= $isEdit ? 'Salvar alterações' : 'Criar projeto' ?></button>
        <a class="btn btn--ghost" href="<?= e(url('admin/projetos')) ?>">Cancelar</a>
    </div>
</form>

<?php if ($isEdit): ?>
    <div class="card" style="margin-top:26px">
        <div class="card__head">Galeria de imagens</div>
        <div class="card__body">
            <form method="post" action="<?= e(url('admin/projetos/' . $project['id'] . '/imagens')) ?>" enctype="multipart/form-data" class="form-grid" style="margin-bottom:20px">
                <?= csrf_field() ?>
                <div class="field">
                    <label for="gallery">Adicionar imagens (múltiplas)</label>
                    <input class="input" type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                </div>
                <div><button class="btn btn--dark" type="submit">Enviar imagens</button></div>
            </form>

            <?php if (empty($images)): ?>
                <p style="color:var(--text-muted)">Nenhuma imagem na galeria ainda.</p>
            <?php else: ?>
                <div class="gallery-grid">
                    <?php foreach ($images as $img): ?>
                        <div class="gallery-item">
                            <img src="<?= e(upload_url($img['image'])) ?>" alt="<?= e($img['alt_text'] ?? '') ?>" loading="lazy">
                            <form method="post" action="<?= e(url('admin/projetos/imagens/' . $img['id'] . '/excluir')) ?>" data-confirm="Remover esta imagem?">
                                <?= csrf_field() ?>
                                <button class="btn btn--danger" type="submit" title="Remover">✕</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
