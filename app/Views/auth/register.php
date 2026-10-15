<section class="auth-card panel">
  <p class="eyebrow">Únete a la familia Roséa</p><h1>Crear cuenta</h1>
  <form method="post" action="<?= site_url('registro') ?>" class="stack">
    <?= csrf_field() ?>
    <label>Nombre<input name="nombre" value="<?= esc(old('nombre')) ?>" maxlength="100" required></label>
    <label>Apellido<input name="apellido" value="<?= esc(old('apellido')) ?>" maxlength="100" required></label>
    <label>Correo electrónico<input type="email" name="email" value="<?= esc(old('email')) ?>" maxlength="150" required></label>
    <label>Contraseña (mínimo 8 caracteres)<input type="password" name="password" minlength="8" autocomplete="new-password" required></label>
    <button class="btn" type="submit">Crear mi cuenta</button>
  </form>
  <p class="center small">¿Ya tienes cuenta? <a class="accent" href="<?= site_url('login') ?>">Inicia sesión</a></p>
</section>
