<?php
/**
 * Configuração central da aplicação.
 *
 * IMPORTANTE: este projeto NÃO usa arquivo .env.
 * Credenciais sensíveis do banco de dados ficam em config/config.local.php
 * (fora do versionamento, veja .gitignore). Se esse arquivo não existir, os
 * valores padrão abaixo são usados — adequados para desenvolvimento local.
 *
 * Todas as demais configurações (SMTP, empresa, SEO, WhatsApp, redes sociais)
 * são gerenciadas pelo painel administrativo e armazenadas na tabela `settings`.
 */

declare(strict_types=1);

$defaults = [
    // Ambiente: 'development' exibe erros detalhados; 'production' os oculta.
    'env' => 'development',

    // URL base do site (sem barra final). Em produção, ajuste para o domínio real.
    'base_url' => '',

    // Fuso horário padrão da aplicação.
    'timezone' => 'America/Sao_Paulo',

    // Chave usada para criptografar valores sensíveis em `settings` (ex.: senha SMTP)
    // e para assinar tokens. ALTERE em produção para uma string longa e aleatória.
    'app_key' => 'CHANGE-ME-mafedo-dev-key-please-override-in-production',

    // Driver de banco: 'mysql' (produção) ou 'sqlite' (desenvolvimento/testes).
    'db' => [
        'driver'   => 'mysql',
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'database' => 'mafedo',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',

        // Caminho do arquivo SQLite quando driver = 'sqlite'.
        'sqlite_path' => dirname(__DIR__) . '/storage/database/mafedo.sqlite',
    ],

    // Caminho da pasta de uploads acessível publicamente.
    'uploads_path' => dirname(__DIR__) . '/public/uploads',
    'uploads_url'  => '/uploads',
];

// Sobrescreve com config local, se existir (não versionada).
$localFile = __DIR__ . '/config.local.php';
if (is_file($localFile)) {
    $local = require $localFile;
    if (is_array($local)) {
        // Merge recursivo raso para a chave 'db'.
        if (isset($local['db']) && is_array($local['db'])) {
            $defaults['db'] = array_merge($defaults['db'], $local['db']);
            unset($local['db']);
        }
        $defaults = array_merge($defaults, $local);
    }
}

return $defaults;
