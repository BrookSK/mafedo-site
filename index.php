<?php
/**
 * Entrada única da aplicação quando o servidor aponta para a raiz do projeto.
 * Apenas delega para o front controller em /public, sem alterar a URL.
 *
 * Em produção, o ideal é configurar o DocumentRoot do servidor diretamente para
 * a pasta /public. Este arquivo + o .htaccess da raiz permitem funcionamento em
 * hospedagens que não deixam alterar o DocumentRoot.
 */

declare(strict_types=1);

require __DIR__ . '/public/index.php';
