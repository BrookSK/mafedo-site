<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Service;
use App\Services\UploadService;

final class ServiceController extends Controller
{
    private Service $services;

    public function __construct(\App\Core\Request $request)
    {
        parent::__construct($request);
        $this->services = new Service();
    }

    public function index(): void
    {
        $this->adminView('admin/services/index', [
            'title'    => 'Serviços',
            'services' => $this->services->paginatedAdmin(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/services/form', [
            'title'   => 'Novo serviço',
            'service' => null,
            'errors'  => Session::flash('errors') ?? [],
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = $this->validateInput();

        if ($data === null) {
            $this->redirect('admin/servicos/criar');
        }

        $data['slug'] = $this->services->uniqueSlug($this->request->str('slug') ?: $data['title']);

        if ($image = $this->handleImage()) {
            $data['image'] = $image;
        }

        $this->services->create($data);
        Session::flash('success', 'Serviço criado com sucesso.');
        $this->redirect('admin/servicos');
    }

    public function edit(string $id): void
    {
        $service = $this->services->find((int) $id);
        if ($service === null) {
            Session::flash('error', 'Serviço não encontrado.');
            $this->redirect('admin/servicos');
        }

        $this->adminView('admin/services/form', [
            'title'   => 'Editar serviço',
            'service' => $service,
            'errors'  => Session::flash('errors') ?? [],
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $service = $this->services->find((int) $id);
        if ($service === null) {
            Session::flash('error', 'Serviço não encontrado.');
            $this->redirect('admin/servicos');
        }

        $data = $this->validateInput();
        if ($data === null) {
            $this->redirect('admin/servicos/' . (int) $id . '/editar');
        }

        $newSlug = $this->request->str('slug') ?: $data['title'];
        $data['slug'] = $this->services->uniqueSlug($newSlug, (int) $id);

        if ($image = $this->handleImage()) {
            UploadService::delete($service['image'] ?? null);
            $data['image'] = $image;
        }

        $this->services->update((int) $id, $data);
        Session::flash('success', 'Serviço atualizado.');
        $this->redirect('admin/servicos');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        $service = $this->services->find((int) $id);
        if ($service !== null) {
            UploadService::delete($service['image'] ?? null);
            $this->services->delete((int) $id);
            Session::flash('success', 'Serviço excluído.');
        }
        $this->redirect('admin/servicos');
    }

    public function toggle(string $id): void
    {
        $this->verifyCsrf();
        $service = $this->services->find((int) $id);
        if ($service !== null) {
            $this->services->update((int) $id, ['status' => (int) $service['status'] === 1 ? 0 : 1]);
            Session::flash('success', 'Status atualizado.');
        }
        $this->redirect('admin/servicos');
    }

    /** @return array<string,mixed>|null */
    private function validateInput(): ?array
    {
        $input = $this->request->all();
        $validator = new Validator($input, [
            'title'             => 'required|max:160',
            'short_description' => 'max:320',
            'seo_title'         => 'max:180',
            'seo_description'   => 'max:320',
        ], ['title' => 'Título', 'short_description' => 'Descrição curta']);

        if ($validator->fails()) {
            $this->flashOld($input);
            $this->flashErrors($validator->errors());
            Session::flash('error', 'Corrija os campos destacados.');
            return null;
        }

        return [
            'title'             => $this->request->str('title'),
            'short_description' => $this->request->str('short_description'),
            'description'       => (string) $this->request->input('description', ''),
            'icon'              => $this->request->str('icon'),
            'featured'          => $this->request->input('featured') ? 1 : 0,
            'status'            => $this->request->input('status') ? 1 : 0,
            'sort_order'        => (int) $this->request->input('sort_order', 0),
            'seo_title'         => $this->request->str('seo_title'),
            'seo_description'   => $this->request->str('seo_description'),
        ];
    }

    private function handleImage(): ?string
    {
        $file = $this->request->file('image');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        try {
            return UploadService::store($file, 'servicos');
        } catch (\RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            return null;
        }
    }
}
