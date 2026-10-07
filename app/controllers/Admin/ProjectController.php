<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\UploadService;

final class ProjectController extends Controller
{
    private Project $projects;
    private ProjectImage $images;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->projects = new Project();
        $this->images = new ProjectImage();
    }

    public function index(): void
    {
        $this->adminView('admin/projects/index', [
            'title'    => 'Projetos',
            'projects' => $this->projects->paginatedAdmin(),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/projects/form', [
            'title'   => 'Novo projeto',
            'project' => null,
            'images'  => [],
            'errors'  => Session::flash('errors') ?? [],
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = $this->validateInput();
        if ($data === null) {
            $this->redirect('admin/projetos/criar');
        }

        $data['slug'] = $this->projects->uniqueSlug($this->request->str('slug') ?: $data['title']);

        if ($image = $this->handleMainImage()) {
            $data['main_image'] = $image;
        }

        $id = $this->projects->create($data);

        // Galeria enviada junto na criação (opcional).
        $this->handleGalleryUpload($id);

        Session::flash('success', 'Projeto criado com sucesso. Agora você pode adicionar imagens à galeria.');
        $this->redirect('admin/projetos/' . $id . '/editar');
    }

    public function edit(string $id): void
    {
        $project = $this->projects->find((int) $id);
        if ($project === null) {
            Session::flash('error', 'Projeto não encontrado.');
            $this->redirect('admin/projetos');
        }

        $this->adminView('admin/projects/form', [
            'title'   => 'Editar projeto',
            'project' => $project,
            'images'  => $this->images->forProject((int) $id),
            'errors'  => Session::flash('errors') ?? [],
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $project = $this->projects->find((int) $id);
        if ($project === null) {
            Session::flash('error', 'Projeto não encontrado.');
            $this->redirect('admin/projetos');
        }

        $data = $this->validateInput();
        if ($data === null) {
            $this->redirect('admin/projetos/' . (int) $id . '/editar');
        }

        $data['slug'] = $this->projects->uniqueSlug($this->request->str('slug') ?: $data['title'], (int) $id);

        if ($image = $this->handleMainImage()) {
            UploadService::delete($project['main_image'] ?? null);
            $data['main_image'] = $image;
        }

        $this->projects->update((int) $id, $data);
        Session::flash('success', 'Projeto atualizado.');
        $this->redirect('admin/projetos/' . (int) $id . '/editar');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        $project = $this->projects->find((int) $id);
        if ($project !== null) {
            // Remove arquivos físicos (principal + galeria).
            UploadService::delete($project['main_image'] ?? null);
            foreach ($this->images->forProject((int) $id) as $img) {
                UploadService::delete($img['image']);
            }
            // FK ON DELETE CASCADE remove as linhas de project_images.
            $this->projects->delete((int) $id);
            Session::flash('success', 'Projeto excluído.');
        }
        $this->redirect('admin/projetos');
    }

    public function toggle(string $id): void
    {
        $this->verifyCsrf();
        $project = $this->projects->find((int) $id);
        if ($project !== null) {
            $this->projects->update((int) $id, ['status' => (int) $project['status'] === 1 ? 0 : 1]);
            Session::flash('success', 'Status atualizado.');
        }
        $this->redirect('admin/projetos');
    }

    public function uploadImages(string $id): void
    {
        $this->verifyCsrf();
        $project = $this->projects->find((int) $id);
        if ($project === null) {
            Session::flash('error', 'Projeto não encontrado.');
            $this->redirect('admin/projetos');
        }

        $count = $this->handleGalleryUpload((int) $id);
        Session::flash($count > 0 ? 'success' : 'error', $count > 0
            ? "{$count} imagem(ns) adicionada(s) à galeria."
            : 'Nenhuma imagem válida foi enviada.');
        $this->redirect('admin/projetos/' . (int) $id . '/editar');
    }

    public function deleteImage(string $imageId): void
    {
        $this->verifyCsrf();
        $img = $this->images->find((int) $imageId);
        if ($img !== null) {
            UploadService::delete($img['image']);
            $this->images->delete((int) $imageId);
            Session::flash('success', 'Imagem removida.');
            $this->redirect('admin/projetos/' . (int) $img['project_id'] . '/editar');
        }
        $this->redirect('admin/projetos');
    }

    // -------------------------------------------------------------------

    /** @return array<string,mixed>|null */
    private function validateInput(): ?array
    {
        $input = $this->request->all();
        $validator = new Validator($input, [
            'title'             => 'required|max:180',
            'short_description' => 'max:320',
            'category'          => 'max:120',
            'location'          => 'max:160',
            'year'              => 'max:9',
            'client'            => 'max:160',
            'segment'           => 'max:120',
            'seo_title'         => 'max:180',
            'seo_description'   => 'max:320',
        ], ['title' => 'Título']);

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
            'category'          => $this->request->str('category'),
            'location'          => $this->request->str('location'),
            'year'              => $this->request->str('year'),
            'client'            => $this->request->str('client'),
            'segment'           => $this->request->str('segment'),
            'characteristics'   => (string) $this->request->input('characteristics', ''),
            'featured'          => $this->request->input('featured') ? 1 : 0,
            'status'            => $this->request->input('status') ? 1 : 0,
            'sort_order'        => (int) $this->request->input('sort_order', 0),
            'seo_title'         => $this->request->str('seo_title'),
            'seo_description'   => $this->request->str('seo_description'),
        ];
    }

    private function handleMainImage(): ?string
    {
        $file = $this->request->file('main_image');
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        try {
            return UploadService::store($file, 'projetos');
        } catch (\RuntimeException $e) {
            Session::flash('error', $e->getMessage());
            return null;
        }
    }

    /** Processa múltiplos arquivos de galeria (input name="gallery[]"). */
    private function handleGalleryUpload(int $projectId): int
    {
        $files = $this->request->files('gallery');
        if ($files === null || !isset($files['name']) || !is_array($files['name'])) {
            return 0;
        }

        $added = 0;
        $sort = $this->images->nextSortOrder($projectId);
        $total = count($files['name']);

        for ($i = 0; $i < $total; $i++) {
            if (($files['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                continue;
            }
            $single = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];
            try {
                $stored = UploadService::store($single, 'projetos/galeria');
                $this->images->create([
                    'project_id' => $projectId,
                    'image'      => $stored,
                    'alt_text'   => '',
                    'sort_order' => $sort++,
                ]);
                $added++;
            } catch (\RuntimeException) {
                // Ignora arquivos inválidos individualmente.
                continue;
            }
        }
        return $added;
    }
}
