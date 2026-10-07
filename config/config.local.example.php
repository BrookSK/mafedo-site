<?php
/**
 * EXEMPLO de configuração local.
 *
 * Copie este arquivo para `config/config.local.php` e ajuste os valores do seu
 * ambiente. O arquivo config.local.php NÃO é versionado (veja .gitignore) e tem
 * precedência sobre os padrões de config/config.php.
 *
 * Este é o único lugar com credenciais do banco — todo o resto é configurável
 * pelo painel administrativo.
 */

declare(strict_types=1);

return [
    'env'      => 'production',
    'base_url' => 'https://www.mafedo.com.br',

    // Gere uma chave aleatória longa (ex.: bin2hex(random_bytes(32))).
    'app_key'  => 'TROQUE-POR-UMA-CHAVE-ALEATORIA-LONGA',

    'db' => [
        'driver'   => 'mysql',
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'database' => 'mafedo',
        'username' => 'mafedo_user',
        'password' => 'senha-forte-aqui',
        'charset'  => 'utf8mb4',
    ],
];
