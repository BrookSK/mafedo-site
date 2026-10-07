<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Logger;
use App\Core\Session;
use App\Core\Validator;
use App\Services\LoginThrottle;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        echo \App\Core\View::render('admin/auth/login', [
            'title' => 'Entrar',
        ], 'layouts/auth');
    }

    public function login(): void
    {
        $this->verifyCsrf();

        $email = mb_strtolower($this->request->str('email'));
        $password = (string) $this->request->input('password', '');
        $ip = $this->request->ip();

        $validator = new Validator(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => 'required'],
            ['email' => 'E-mail', 'password' => 'Senha']
        );

        if ($validator->fails()) {
            $this->flashOld(['email' => $email]);
            Session::flash('error', 'Informe e-mail e senha válidos.');
            $this->redirect('admin/login');
        }

        // Proteção contra brute force
        if (LoginThrottle::tooManyAttempts($email, $ip)) {
            Logger::warning("Login bloqueado por brute force: {$email} / {$ip}");
            Session::flash('error', 'Muitas tentativas de login. Tente novamente em alguns minutos.');
            $this->redirect('admin/login');
        }

        $user = Auth::attempt($email, $password);

        if ($user === null) {
            LoginThrottle::record($email, $ip, false);
            $this->flashOld(['email' => $email]);
            Session::flash('error', 'Credenciais inválidas.');
            $this->redirect('admin/login');
        }

        LoginThrottle::record($email, $ip, true);
        LoginThrottle::clear($email, $ip);
        Auth::login($user);
        Logger::info("Login bem-sucedido: {$email}");

        $intended = Session::get('intended_url');
        Session::remove('intended_url');

        $this->redirect($intended && str_starts_with($intended, '/admin') ? ltrim($intended, '/') : 'admin');
    }

    public function logout(): void
    {
        $this->verifyCsrf();
        Auth::logout();
        Session::flash('success', 'Você saiu com segurança.');
        $this->redirect('admin/login');
    }
}
