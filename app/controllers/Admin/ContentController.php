<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Setting;
use App\Services\UploadService;

/**
 * Painel > Conteúdo do site: edição de todos os textos e imagens das páginas.
 * A estrutura (páginas, seções, campos e padrões) vem de content_schema().
 */
final class ContentController extends Controller
{
    private const MAX_LEN = 20000;

    public function index(): void
    {
        $schema = content_schema();
        $page = $this->request->str('pagina', 'geral');
        if (!isset($schema[$page])) {
            $page = 'geral';
        }

        $this->adminView('admin/content/index', [
            'title'  => 'Conteúdo do site',
            'schema' => $schema,
            'page'   => $page,
        ]);
    }

    public function save(string $page): void
    {
        $this->verifyCsrf();
        $schema = content_schema();
        if (!isset($schema[$page])) {
            $this->redirect('admin/conteudo');
        }

        $errors = [];
        $posted = $this->request->all();
        $reset = (array) ($this->request->input('reset_section') ?? []);
        foreach ($schema[$page]['sections'] as $sectionKey => $section) {
            // "Restaurar originais" da seção: apaga o que foi editado.
            if (in_array($sectionKey, $reset, true)) {
                foreach (content_section_fields($section) as $field => $def) {
                    $key = 'content.' . $page . '.' . $field;
                    $current = Setting::get($key);
                    if ($current === null) {
                        continue;
                    }
                    if ($def['type'] === 'image' && !str_starts_with((string) $current, '/')) {
                        UploadService::delete((string) $current);
                    }
                    Setting::forget($key);
                }
                continue;
            }

            foreach (content_section_fields($section) as $field => $def) {
                $key = 'content.' . $page . '.' . $field;

                if ($def['type'] === 'image') {
                    $error = $this->saveImage($key, $field, $def);
                    if ($error !== null) {
                        $errors[] = $def['label'] . ': ' . $error;
                    }
                    continue;
                }

                // Campo não enviado no formulário: não mexe (evita apagar por engano).
                if (!array_key_exists($field, $posted)) {
                    continue;
                }
                $value = (string) $posted[$field];
                $value = str_replace("\r\n", "\n", trim($value));
                $value = mb_substr($value, 0, self::MAX_LEN);
                if ($def['type'] === 'icon' && !isset(content_icon_options()[$value])) {
                    $value = $def['default'];
                }

                // Igual ao padrão: não guarda nada (o site segue o padrão).
                if ($value === $def['default']) {
                    if (Setting::get($key) !== null) {
                        Setting::forget($key);
                    }
                } elseif (Setting::get($key) !== $value) {
                    Setting::set($key, $value, 'content');
                }
            }
        }

        Setting::clearCache();
        if ($errors) {
            Session::flash('error', 'Textos salvos, mas houve problema com imagens: ' . implode(' | ', $errors));
        } else {
            Session::flash('success', 'Conteúdo salvo. As alterações já estão no site.');
        }
        $this->redirect('admin/conteudo?pagina=' . $page);
    }

    /** Troca, mantém ou restaura (padrão) uma imagem. Retorna mensagem de erro ou null. */
    private function saveImage(string $key, string $field, array $def): ?string
    {
        $current = Setting::get($key);
        $isUpload = static fn ($v) => is_string($v) && $v !== '' && !str_starts_with($v, '/') && !preg_match('#^https?://#', $v);

        if ($this->request->input($field . '_reset')) {
            if ($isUpload($current)) {
                UploadService::delete($current);
            }
            if ($current !== null) {
                Setting::forget($key);
            }
            return null;
        }

        $file = $this->request->file($field);
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        try {
            $stored = UploadService::store($file, 'conteudo');
        } catch (\RuntimeException $e) {
            return $e->getMessage();
        }
        if ($isUpload($current)) {
            UploadService::delete($current);
        }
        Setting::set($key, $stored, 'content');
        return null;
    }
}
