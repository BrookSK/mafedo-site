<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Core\Validator;
use App\Models\Role;
use App\Models\User;

final class UserController extends Controller
{
    private User $users;
    private Role $roles;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->users = new User();
        $this->roles = new Role();
    }

    public function index(): void
    {
        $list = $this->users->all('name');
        foreach ($list as &$u) {
            $u['roles'] = $this->users->roleNames((int) $u['id']);
        }
        unset($u);

        $this->adminView('admin/users/index', [
            'title' => 'Usuários',
            'users' => $list,
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/users/form', [
            'title'     => 'Novo usuário',
            'user'      => null,
            'roles'     => $this->roles->all('name'),
            'userRoles' => [],
            'errors'    => Session::flash('errors') ?? [],
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $input = $this->request->all();

        $validator = new Validator($input, [
            'name'     => 'required|max:120',
            'email'    => 'required|email|max:160',
            'password' => 'required|min:8|confirmed',
        ], ['name' => 'Nome', 'email' => 'E-mail', 'password' => 'Senha']);

        if ($validator->fails()) {
            $this->flashOld($input);
            $this->flashErrors($validator->errors());
            $this->redirect('admin/usuarios/criar');
        }

        $email = mb_strtolower($this->request->str('email'));
        if ($this->users->emailExists($email)) {
            $this->flashOld($input);
            $this->flashErrors(['email' => 'Este e-mail já está em uso.']);
            $this->redirect('admin/usuarios/criar');
        }

        $id = $this->users->create([
            'name'     => $this->request->str('name'),
            'email'    => $email,
            'password' => password_hash((string) $this->request->input('password'), PASSWORD_DEFAULT),
            'status'   => $this->request->input('status') ? 1 : 0,
        ]);

        $this->users->syncRoles($id, (array) $this->request->input('roles', []));
        Session::flash('success', 'Usuário criado.');
        $this->redirect('admin/usuarios');
    }

    public function edit(string $id): void
    {
        $user = $this->users->find((int) $id);
        if ($user === null) {
            Session::flash('error', 'Usuário não encontrado.');
            $this->redirect('admin/usuarios');
        }

        $this->adminView('admin/users/form', [
            'title'     => 'Editar usuário',
            'user'      => $user,
            'roles'     => $this->roles->all('name'),
            'userRoles' => $this->users->roleIds((int) $id),
            'errors'    => Session::flash('errors') ?? [],
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $user = $this->users->find((int) $id);
        if ($user === null) {
            Session::flash('error', 'Usuário não encontrado.');
            $this->redirect('admin/usuarios');
        }

        $input = $this->request->all();
        $rules = [
            'name'  => 'required|max:120',
            'email' => 'required|email|max:160',
        ];
        $password = (string) $this->request->input('password', '');
        if ($password !== '') {
            $rules['password'] = 'min:8|confirmed';
        }

        $validator = new Validator($input, $rules, ['name' => 'Nome', 'email' => 'E-mail', 'password' => 'Senha']);
        if ($validator->fails()) {
            $this->flashOld($input);
            $this->flashErrors($validator->errors());
            $this->redirect('admin/usuarios/' . (int) $id . '/editar');
        }

        $email = mb_strtolower($this->request->str('email'));
        if ($this->users->emailExists($email, (int) $id)) {
            $this->flashOld($input);
            $this->flashErrors(['email' => 'Este e-mail já está em uso.']);
            $this->redirect('admin/usuarios/' . (int) $id . '/editar');
        }

        $newStatus = $this->request->input('status') ? 1 : 0;

        // Impede desativar o último super admin ativo.
        if ($newStatus === 0 && $this->users->isSuperAdmin((int) $id) && $this->users->activeSuperAdminCount() <= 1) {
            Session::flash('error', 'Não é possível desativar o último Super Admin ativo.');
            $this->redirect('admin/usuarios/' . (int) $id . '/editar');
        }

        $this->users->update((int) $id, [
            'name'   => $this->request->str('name'),
            'email'  => $email,
            'status' => $newStatus,
        ]);

        if ($password !== '') {
            $this->users->updatePassword((int) $id, $password);
        }

        // Protege o papel do último super admin.
        $roleIds = array_map('intval', (array) $this->request->input('roles', []));
        if ($this->users->isSuperAdmin((int) $id) && $this->users->activeSuperAdminCount() <= 1) {
            $superRole = $this->roles->findBy('name', 'super-admin');
            if ($superRole && !in_array((int) $superRole['id'], $roleIds, true)) {
                $roleIds[] = (int) $superRole['id'];
                Session::flash('error', 'O papel Super Admin foi mantido (é o último administrador).');
            }
        }
        $this->users->syncRoles((int) $id, $roleIds);

        Session::flash('success', 'Usuário atualizado.');
        $this->redirect('admin/usuarios');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();

        if ((int) $id === Auth::id()) {
            Session::flash('error', 'Você não pode excluir a própria conta.');
            $this->redirect('admin/usuarios');
        }

        if ($this->users->isSuperAdmin((int) $id) && $this->users->activeSuperAdminCount() <= 1) {
            Session::flash('error', 'Não é possível excluir o último Super Admin.');
            $this->redirect('admin/usuarios');
        }

        $this->users->delete((int) $id);
        Session::flash('success', 'Usuário excluído.');
        $this->redirect('admin/usuarios');
    }
}
