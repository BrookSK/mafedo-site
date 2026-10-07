<?php

declare(strict_types=1);

use App\Core\Router;

/**
 * Definição das rotas da aplicação.
 *
 * Retorna uma função que recebe o Router e registra todas as rotas.
 * Handlers no formato 'Namespace\\Controller@metodo' (relativo a App\Controllers).
 */
return function (Router $router): void {

    // ---------------------------------------------------------------------
    // SITE (frontend institucional)
    // ---------------------------------------------------------------------
    $router->get('/', 'Site\\HomeController@index');
    $router->get('/sobre', 'Site\\PageController@about');
    $router->get('/servicos', 'Site\\ServiceController@index');
    $router->get('/servicos/{slug}', 'Site\\ServiceController@show');
    $router->get('/projetos', 'Site\\ProjectController@index');
    $router->get('/projetos/{slug}', 'Site\\ProjectController@show');

    $router->get('/contato', 'Site\\ContactController@index');
    $router->post('/contato', 'Site\\ContactController@submit');

    $router->get('/politica-de-privacidade', 'Site\\PageController@privacy');
    $router->get('/termos-de-uso', 'Site\\PageController@terms');

    // SEO
    $router->get('/sitemap.xml', 'Site\\SeoController@sitemap');
    $router->get('/robots.txt', 'Site\\SeoController@robots');

    // ---------------------------------------------------------------------
    // ADMIN (área restrita)
    // ---------------------------------------------------------------------
    $router->group(['prefix' => '/admin'], function (Router $router): void {

        // Login (apenas visitantes)
        $router->get('/login', 'Admin\\AuthController@showLogin', ['guest']);
        $router->post('/login', 'Admin\\AuthController@login', ['guest']);
        $router->post('/logout', 'Admin\\AuthController@logout', ['auth']);

        // Dashboard
        $router->get('', 'Admin\\DashboardController@index', ['auth', 'permission:dashboard.view']);
        $router->get('/', 'Admin\\DashboardController@index', ['auth', 'permission:dashboard.view']);

        // Serviços
        $router->get('/servicos', 'Admin\\ServiceController@index', ['auth', 'permission:services.view']);
        $router->get('/servicos/criar', 'Admin\\ServiceController@create', ['auth', 'permission:services.create']);
        $router->post('/servicos', 'Admin\\ServiceController@store', ['auth', 'permission:services.create']);
        $router->get('/servicos/{id}/editar', 'Admin\\ServiceController@edit', ['auth', 'permission:services.edit']);
        $router->post('/servicos/{id}', 'Admin\\ServiceController@update', ['auth', 'permission:services.edit']);
        $router->post('/servicos/{id}/excluir', 'Admin\\ServiceController@destroy', ['auth', 'permission:services.delete']);
        $router->post('/servicos/{id}/toggle', 'Admin\\ServiceController@toggle', ['auth', 'permission:services.edit']);

        // Projetos
        $router->get('/projetos', 'Admin\\ProjectController@index', ['auth', 'permission:projects.view']);
        $router->get('/projetos/criar', 'Admin\\ProjectController@create', ['auth', 'permission:projects.create']);
        $router->post('/projetos', 'Admin\\ProjectController@store', ['auth', 'permission:projects.create']);
        $router->get('/projetos/{id}/editar', 'Admin\\ProjectController@edit', ['auth', 'permission:projects.edit']);
        $router->post('/projetos/{id}', 'Admin\\ProjectController@update', ['auth', 'permission:projects.edit']);
        $router->post('/projetos/{id}/excluir', 'Admin\\ProjectController@destroy', ['auth', 'permission:projects.delete']);
        $router->post('/projetos/{id}/toggle', 'Admin\\ProjectController@toggle', ['auth', 'permission:projects.edit']);
        // Galeria de imagens do projeto
        $router->post('/projetos/{id}/imagens', 'Admin\\ProjectController@uploadImages', ['auth', 'permission:projects.edit']);
        $router->post('/projetos/imagens/{imageId}/excluir', 'Admin\\ProjectController@deleteImage', ['auth', 'permission:projects.edit']);

        // Mensagens de contato
        $router->get('/mensagens', 'Admin\\MessageController@index', ['auth', 'permission:messages.view']);
        $router->get('/mensagens/{id}', 'Admin\\MessageController@show', ['auth', 'permission:messages.view']);
        $router->post('/mensagens/{id}/status', 'Admin\\MessageController@setStatus', ['auth', 'permission:messages.view']);
        $router->post('/mensagens/{id}/excluir', 'Admin\\MessageController@destroy', ['auth', 'permission:messages.delete']);

        // Usuários
        $router->get('/usuarios', 'Admin\\UserController@index', ['auth', 'permission:users.view']);
        $router->get('/usuarios/criar', 'Admin\\UserController@create', ['auth', 'permission:users.create']);
        $router->post('/usuarios', 'Admin\\UserController@store', ['auth', 'permission:users.create']);
        $router->get('/usuarios/{id}/editar', 'Admin\\UserController@edit', ['auth', 'permission:users.edit']);
        $router->post('/usuarios/{id}', 'Admin\\UserController@update', ['auth', 'permission:users.edit']);
        $router->post('/usuarios/{id}/excluir', 'Admin\\UserController@destroy', ['auth', 'permission:users.delete']);

        // Configurações
        $router->get('/configuracoes', 'Admin\\SettingController@index', ['auth', 'permission:settings.view']);
        $router->post('/configuracoes/geral', 'Admin\\SettingController@saveGeneral', ['auth', 'permission:settings.edit']);
        $router->post('/configuracoes/smtp', 'Admin\\SettingController@saveSmtp', ['auth', 'permission:settings.edit']);
        $router->post('/configuracoes/smtp/teste', 'Admin\\SettingController@testSmtp', ['auth', 'permission:settings.edit']);
        $router->post('/configuracoes/seo', 'Admin\\SettingController@saveSeo', ['auth', 'permission:settings.edit']);
        $router->post('/configuracoes/whatsapp', 'Admin\\SettingController@saveWhatsapp', ['auth', 'permission:settings.edit']);
        $router->post('/configuracoes/redes', 'Admin\\SettingController@saveSocial', ['auth', 'permission:settings.edit']);
    });
};
