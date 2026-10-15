<?php helper(['url','form']); ?>
<!doctype html>
<html lang="es"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= esc($title ?? 'Roséa Boutique') ?> | Roséa Boutique</title>
<link rel="stylesheet" href="<?= base_url('assets/css/rosea.css') ?>">
</head><body>
<header class="navbar">
  <a class="brand" href="<?= site_url('/') ?>">✿ Roséa Boutique</a>
  <nav aria-label="Navegación principal">
    <a href="<?= site_url('productos') ?>">Colección</a>
    <?php if (session('id_usuario')): ?>
      <a href="<?= site_url('perfil') ?>">Mi perfil</a>
      <?php if (session('rol') === 'admin'): ?><a href="<?= site_url('admin') ?>">Administración</a><?php endif ?>
      <form class="inline-form" action="<?= site_url('logout') ?>" method="post"><?= csrf_field() ?><button class="text-btn" type="submit">Salir</button></form>
    <?php else: ?>
      <a href="<?= site_url('login') ?>">Iniciar sesión</a><a href="<?= site_url('registro') ?>">Registro</a>
    <?php endif ?>
  </nav>
</header>
<main class="container">
<?php if ($notice = session()->getFlashdata('success')): ?><div class="alert success" role="status"><?= esc($notice) ?></div><?php endif ?>
<?php if ($notice = session()->getFlashdata('error')): ?><div class="alert error" role="alert"><?= esc($notice) ?></div><?php endif ?>
