<section class="auth-card panel">
  <p class="eyebrow">Bienvenida de nuevo</p><h1>Iniciar sesión</h1>
  <p>Ingresa para realizar tus compras y consultar tus pedidos.</p>
  <form method="post" action="<?= site_url('login') ?>" class="stack">
    <?= csrf_field() ?>
    <label>Correo electrónico<input type="email" name="email" value="<?= esc(old('email')) ?>" maxlength="150" autocomplete="email" required></label>
    <label>Contraseña<input type="password" name="password" autocomplete="current-password" required></label>
    <button class="btn" type="submit">Entrar a mi cuenta</button>
  </form>
  <p class="center small">¿No tienes cuenta? <a class="accent" href="<?= site_url('registro') ?>">Regístrate aquí</a></p>
</section>
