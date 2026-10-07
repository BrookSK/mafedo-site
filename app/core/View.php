<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Engine de views baseada em PHP puro, com suporte a layout e seções.
 *
 * Uso típico num controller:
 *   View::render('site/home', ['projects' => $projects], 'layouts/site');
 *
 * Dentro da view:
 *   <?= e($title) ?>              // saída escapada
 *   <?php $this->section('head'); ?> ... <?php $this->endSection(); ?>
 *
 * No layout:
 *   <?= $this->content() ?>       // corpo da view
 *   <?= $this->yield('head') ?>   // seção nomeada
 */
final class View
{
    private static string $viewsPath = '';
    private array $sections = [];
    private array $sectionStack = [];
    private string $renderedContent = '';

    public static function setViewsPath(string $path): void
    {
        self::$viewsPath = rtrim($path, '/\\');
    }

    public static function render(string $view, array $data = [], ?string $layout = null): string
    {
        $instance = new self();
        return $instance->doRender($view, $data, $layout);
    }

    public static function exists(string $view): bool
    {
        return is_file(self::$viewsPath . '/' . $view . '.php');
    }

    private function doRender(string $view, array $data, ?string $layout): string
    {
        $content = $this->renderFile($view, $data);

        if ($layout === null) {
            return $content;
        }

        $this->renderedContent = $content;
        return $this->renderFile($layout, $data);
    }

    private function renderFile(string $view, array $data): string
    {
        $file = self::$viewsPath . '/' . $view . '.php';
        if (!is_file($file)) {
            throw new RuntimeException('View não encontrada: ' . $view);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }

    public function content(): string
    {
        return $this->renderedContent;
    }

    public function section(string $name): void
    {
        $this->sectionStack[] = $name;
        ob_start();
    }

    public function endSection(): void
    {
        $name = array_pop($this->sectionStack);
        if ($name !== null) {
            $this->sections[$name] = ob_get_clean();
        }
    }

    public function yield(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    /** Inclui uma partial, passando dados. */
    public function partial(string $view, array $data = []): void
    {
        echo $this->renderFile($view, $data);
    }
}
