<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class ShopController extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        helper(['url', 'form']);
    }

    protected function render(string $view, array $data = []): string
    {
        helper(['url', 'form']);
        return view('layouts/header', $data) . view($view, $data) . view('layouts/footer');
    }

    protected function guardUser(): ?RedirectResponse
    {
        helper('url');
        if (! session()->has('id_usuario')) {
            return redirect()->to(site_url('login'))->with('error', 'Inicia sesión para continuar.');
        }
        return null;
    }

    protected function guardAdmin(): ?RedirectResponse
    {
        if ($r = $this->guardUser()) {
            return $r;
        }
        if (session('rol') !== 'admin') {
            return redirect()->to(site_url('productos'))->with('error', 'Acceso exclusivo para administradores.');
        }
        return null;
    }
}
