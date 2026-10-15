<p class="eyebrow"><a href="<?= site_url('admin/productos') ?>">← Volver a productos</a></p><h1>Editar: <?= esc($product['nombre']) ?></h1>
<div class="panel narrow"><form class="stack" method="post" enctype="multipart/form-data" action="<?= site_url('admin/productos/'.$product['id_producto']) ?>">
<?= csrf_field() ?><label>Nombre<input name="nombre" maxlength="150" value="<?= esc($product['nombre']) ?>" required></label>
<label>Categoría<input name="categoria" maxlength="100" value="<?= esc($product['categoria']) ?>" required></label>
<label>Descripción<textarea name="descripcion"><?= esc($product['descripcion']) ?></textarea></label>
<div class="two-inputs"><label>Precio RD$<input type="number" name="precio" min="0.01" step="0.01" value="<?= esc($product['precio']) ?>" required></label><label>Stock<input type="number" name="stock" min="0" value="<?= (int)$product['stock'] ?>" required></label></div>
<?php if ($product['imagen']): ?><img class="thumb" src="<?= base_url('img/'.rawurlencode(basename($product['imagen']))) ?>" alt="Imagen del producto"><?php endif ?>
<label>Cambiar imagen<input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp"></label>
<label>Visibilidad<select name="disponible"><option value="1" <?= $product['disponible'] ? 'selected' : '' ?>>Disponible</option><option value="0" <?= !$product['disponible'] ? 'selected' : '' ?>>Oculto</option></select></label>
<button type="submit" class="btn">Guardar cambios</button></form></div>
