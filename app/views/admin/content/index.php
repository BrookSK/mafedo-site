<?php
use App\Models\Setting;

$current = $schema[$page];
$value = static fn (string $field): string => c($page . '.' . $field);
$isEdited = static fn (string $field): bool => Setting::get('content.' . $page . '.' . $field) !== null;
$icons = content_icon_options();
?>
<div class="breadcrumbs"><a href="<?= e(url('admin')) ?>">Dashboard</a> / Conteúdo do site</div>
<div class="page-head">
    <h1>Conteúdo do site</h1>
    <?php if (!empty($current['url'])): ?>
        <a class="btn btn--ghost btn--sm" href="<?= e(url($current['url'])) ?>" target="_blank" rel="noopener">Ver esta página no site ↗</a>
    <?php endif; ?>
</div>

<div class="card" style="margin-bottom:20px">
    <div class="card__body content-tabs">
        <?php foreach ($schema as $key => $p): ?>
            <a class="btn <?= $page === $key ? 'btn--dark' : 'btn--ghost' ?> btn--sm" href="<?= e(url('admin/conteudo?pagina=' . $key)) ?>"><?= e($p['label']) ?></a>
        <?php endforeach; ?>
    </div>
</div>

<?php if (!empty($current['intro'])): ?>
    <div class="alert alert--info"><?= e($current['intro']) ?></div>
<?php endif; ?>

<form method="post" action="<?= e(url('admin/conteudo/' . $page)) ?>" enctype="multipart/form-data" class="content-form">
    <?= csrf_field() ?>

    <?php foreach ($current['sections'] as $sectionKey => $section): ?>
        <div class="card" style="margin-bottom:18px">
            <div class="card__head">
                <?= e($section['label']) ?>
                <?php if (!empty($section['hint'])): ?><span class="content-hint"><?= e($section['hint']) ?></span><?php endif; ?>
            </div>
            <div class="card__body">
                <?php
                $fields = content_section_fields($section);
                $lastItem = null;
                ?>
                <div class="form-grid cols-2">
                <?php foreach ($fields as $field => $def):
                    $id = 'f_' . $field;
                    $wide = in_array($def['type'], ['textarea', 'rich', 'image'], true);
                    if (isset($def['item']) && $def['item'] !== $lastItem):
                        $lastItem = $def['item']; ?>
                        <div class="content-item span-2">Item <?= (int) $def['item'] ?></div>
                    <?php endif; ?>

                    <div class="field<?= $wide ? ' span-2' : '' ?>">
                        <label for="<?= e($id) ?>">
                            <?= e($def['label']) ?>
                            <?php if ($isEdited($field)): ?><span class="content-badge">editado</span><?php endif; ?>
                        </label>

                        <?php if ($def['type'] === 'image'): ?>
                            <div class="content-image">
                                <img src="<?= e(upload_url($value($field))) ?>" alt="">
                                <div>
                                    <input class="input" type="file" id="<?= e($id) ?>" name="<?= e($field) ?>" accept="image/jpeg,image/png,image/webp,image/avif">
                                    <span class="hint">Envie uma nova imagem para substituir (JPG, PNG ou WEBP, até 8 MB).</span>
                                    <?php if ($isEdited($field)): ?>
                                        <label class="switch" style="font-weight:400;margin-top:8px"><input type="checkbox" name="<?= e($field) ?>_reset" value="1"> Voltar para a imagem padrão</label>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($def['type'] === 'icon'): ?>
                            <select class="select" id="<?= e($id) ?>" name="<?= e($field) ?>">
                                <?php foreach ($icons as $iconKey => $iconLabel): ?>
                                    <option value="<?= e($iconKey) ?>" <?= $value($field) === $iconKey ? 'selected' : '' ?>><?= e($iconLabel) ?></option>
                                <?php endforeach; ?>
                            </select>

                        <?php elseif ($def['type'] === 'textarea' || $def['type'] === 'rich'): ?>
                            <textarea class="textarea" id="<?= e($id) ?>" name="<?= e($field) ?>" rows="<?= $def['type'] === 'rich' ? 22 : 4 ?>"><?= e($value($field)) ?></textarea>

                        <?php else: ?>
                            <input class="input" id="<?= e($id) ?>" name="<?= e($field) ?>" value="<?= e($value($field)) ?>">
                        <?php endif; ?>

                        <?php if ($def['hint'] !== ''): ?><span class="hint"><?= e($def['hint']) ?></span><?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <?php if (array_filter(array_keys($fields), $isEdited)): ?>
                <div class="card__body content-reset">
                    <label class="switch"><input type="checkbox" name="reset_section[]" value="<?= e($sectionKey) ?>"> Restaurar os textos e imagens originais desta seção ao salvar</label>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div class="content-save">
        <button type="submit" class="btn btn--primary">Salvar <?= e(mb_strtolower($current['label'])) ?></button>
        <span class="hint">Campos marcados como "editado" foram alterados no painel. Um campo deixado vazio não aparece no site.</span>
    </div>
</form>
