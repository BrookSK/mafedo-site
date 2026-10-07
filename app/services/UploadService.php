<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Logger;
use RuntimeException;

/**
 * Upload seguro de imagens.
 *
 * Validações:
 *  - tipo MIME real (finfo), não confia na extensão enviada
 *  - extensão na allowlist
 *  - tamanho máximo
 *  - verifica se é realmente uma imagem (getimagesize)
 *  - gera nome aleatório (evita path traversal e colisões)
 *  - salva em subpasta por ano/mês
 */
final class UploadService
{
    private const MAX_BYTES = 8 * 1024 * 1024; // 8 MB

    /** @var array<string,string> mime => extensão */
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/avif' => 'avif',
        'image/svg+xml' => 'svg',
    ];

    /**
     * Processa um único arquivo ($_FILES['campo']).
     * Retorna o caminho relativo (ex.: '2026/10/abc123.webp') para salvar no banco.
     *
     * @throws RuntimeException em caso de erro de validação
     */
    public static function store(array $file, string $subdir = ''): string
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Envio de arquivo inválido.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('O arquivo excede o tamanho máximo permitido.');
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('Nenhum arquivo enviado.');
            default:
                throw new RuntimeException('Falha no upload do arquivo.');
        }

        if ($file['size'] > self::MAX_BYTES) {
            throw new RuntimeException('A imagem deve ter no máximo 8 MB.');
        }

        // Confirma que o arquivo veio de um upload HTTP legítimo.
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Upload inválido.');
        }

        // Detecta o MIME real.
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = (string) $finfo->file($file['tmp_name']);

        if (!isset(self::ALLOWED[$mime])) {
            throw new RuntimeException('Formato não permitido. Use JPG, PNG, WEBP, GIF, AVIF ou SVG.');
        }

        // Para rasters, confirma que é uma imagem decodificável.
        if ($mime !== 'image/svg+xml') {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                throw new RuntimeException('O arquivo não é uma imagem válida.');
            }
        } else {
            // SVG: sanitização básica removendo scripts/handlers.
            self::sanitizeSvg($file['tmp_name']);
        }

        $ext = self::ALLOWED[$mime];
        $name = bin2hex(random_bytes(16)) . '.' . $ext;

        $relDir = trim($subdir, '/');
        $relDir = ($relDir !== '' ? $relDir . '/' : '') . date('Y/m');
        $baseDir = rtrim((string) Config::get('uploads_path'), '/\\');
        $targetDir = $baseDir . '/' . $relDir;

        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Não foi possível criar o diretório de upload.');
        }

        $targetPath = $targetDir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException('Falha ao salvar o arquivo.');
        }

        @chmod($targetPath, 0644);

        return $relDir . '/' . $name;
    }

    /** Remove um arquivo de upload pelo caminho relativo. */
    public static function delete(?string $relativePath): void
    {
        if ($relativePath === null || $relativePath === '') {
            return;
        }
        // Evita path traversal.
        if (str_contains($relativePath, '..')) {
            return;
        }
        $baseDir = rtrim((string) Config::get('uploads_path'), '/\\');
        $full = $baseDir . '/' . ltrim($relativePath, '/');
        $realBase = realpath($baseDir);
        $realFull = realpath($full);
        if ($realBase !== false && $realFull !== false && str_starts_with($realFull, $realBase) && is_file($realFull)) {
            @unlink($realFull);
        }
    }

    /** Remoção básica de vetores de ataque em SVG. */
    private static function sanitizeSvg(string $path): void
    {
        $content = (string) file_get_contents($path);
        $clean = preg_replace(
            [
                '/<script\b[^>]*>.*?<\/script>/is',
                '/\son\w+\s*=\s*"[^"]*"/i',
                '/\son\w+\s*=\s*\'[^\']*\'/i',
                '/javascript:/i',
            ],
            '',
            $content
        );
        if ($clean !== null && $clean !== $content) {
            file_put_contents($path, $clean);
            Logger::info('SVG sanitizado durante upload: ' . basename($path));
        }
    }
}
