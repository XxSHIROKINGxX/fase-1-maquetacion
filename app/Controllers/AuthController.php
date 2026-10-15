<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends ShopController
{
    public function loginForm()
    {
        if (session()->has('id_usuario')) {
            return redirect()->to(site_url('perfil'));
        }
        return $this->render('auth/login', ['title' => 'Iniciar sesión']);
    }

    public function login()
    {
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');
        $user = (new UsuarioModel())->where('email', $email)->first();
        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Correo o contraseña incorrectos.');
        }
        session()->regenerate();
        session()->set([
            'id_usuario' => (int) $user['id_usuario'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'email' => $user['email'],
            'rol' => $user['rol'],
        ]);
        return redirect()->to(site_url('perfil'))->with('success', 'Has iniciado sesión correctamente.');
    }

    public function registerForm()
    {
        return $this->render('auth/register', ['title' => 'Crear una cuenta']);
    }

    public function register()
    {
        $name = trim((string) $this->request->getPost('nombre'));
        $surname = trim((string) $this->request->getPost('apellido'));
        $email = strtolower(trim((string) $this->request->getPost('email')));
        $password = (string) $this->request->getPost('password');

        if ($name === '' || $surname === '' || strlen($name) > 100 || strlen($surname) > 100 || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150 || strlen($password) < 8) {
            return redirect()->back()->withInput()->with('error', 'Completa los datos con un correo válido y una contraseña de al menos 8 caracteres.');
        }
        $model = new UsuarioModel();
        if ($model->where('email', $email)->first()) {
            return redirect()->back()->withInput()->with('error', 'Ese correo ya está registrado.');
        }
        try {
            $model->insert([
                'nombre' => $name, 'apellido' => $surname, 'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), 'rol' => 'cliente',
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Registro fallido: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'No fue posible registrar la cuenta.');
        }
        return redirect()->to(site_url('login'))->with('success', 'Cuenta creada. Ya puedes iniciar sesión.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'))->with('success', 'Sesión cerrada.');
    }
}
