<p class="eyebrow"><a href="<?= site_url('admin') ?>">← Panel de control</a></p><h1>Administrar usuarios</h1>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Rol</th><th>Cambiar rol</th></tr></thead><tbody>
<?php foreach($users as $u): ?><tr><td>#<?= (int)$u['id_usuario'] ?></td><td><?= esc($u['nombre'].' '.$u['apellido']) ?></td><td><?= esc($u['email']) ?></td><td><?= esc($u['rol']) ?></td><td><form class="inline-form" method="post" action="<?= site_url('admin/usuarios/'.$u['id_usuario'].'/rol') ?>"><?= csrf_field() ?>
<select name="rol" aria-label="Rol de <?= esc($u['nombre']) ?>"><option value="cliente" <?= $u['rol']==='cliente'?'selected':'' ?>>Cliente</option><option value="admin" <?= $u['rol']==='admin'?'selected':'' ?>>Administrador</option></select><button class="btn small-btn">Guardar</button></form></td></tr><?php endforeach ?>
</tbody></table></div>
