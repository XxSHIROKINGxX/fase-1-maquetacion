<p class="eyebrow"><a href="<?= site_url('admin') ?>">← Panel de control</a></p><h1>Administrar productos</h1>
<section class="panel"><h2>Añadir producto</h2>
<form class="stack" method="post" enctype="multipart/form-data" action="<?= site_url('admin/productos') ?>">
<?= csrf_field() ?><div class="two-inputs"><label>Nombre<input name="nombre" maxlength="150" required></label><label>Categoría<input name="categoria" maxlength="100" required></label></div>
<label>Descripción<textarea name="descripcion"></textarea></label>
<div class="two-inputs"><label>Precio en RD$<input type="number" step="0.01" min="0.01" name="precio" required></label><label>Stock<input type="number" min="0" name="stock" required></label></div>
<label>Imagen JPG, PNG o WEBP (2 MB máx.)<input type="file" accept=".jpg,.jpeg,.png,.webp" name="imagen"></label>
<label>Visibilidad<select name="disponible"><option value="1">Disponible</option><option value="0">Oculto</option></select></label>
<button class="btn" type="submit">Guardar producto</button>
</form></section>
<section class="section"><h2>Inventario</h2><div class="table-wrap"><table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Visible</th><th>Acciones</th></tr></thead><tbody>
<?php foreach ($products as $p): ?><tr><td>#<?= (int)$p['id_producto'] ?></td><td><?= esc($p['nombre']) ?></td><td>RD$ <?= number_format((float)$p['precio'],2) ?></td><td><?= (int)$p['stock'] ?></td><td><?= (int)$p['disponible'] === 1 ? 'Sí' : 'No' ?></td><td>
<a href="<?= site_url('admin/productos/'.$p['id_producto'].'/editar') ?>">Editar</a>
<?php if ((int)$p['disponible']===1): ?><form class="inline-form" method="post" action="<?= site_url('admin/productos/'.$p['id_producto'].'/eliminar') ?>"><?= csrf_field() ?><button class="text-btn danger" onclick="return confirm('¿Ocultar este producto?')">Ocultar</button></form><?php endif ?></td></tr><?php endforeach ?>
</tbody></table></div></section>
