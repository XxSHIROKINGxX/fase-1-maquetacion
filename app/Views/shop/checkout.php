<div class="section-heading"><p class="eyebrow">Un último paso</p><h1>Finalizar compra</h1></div>
<div class="two-col">
 <section class="panel">
 <h2>Información de entrega</h2>
 <form class="stack" action="<?= site_url('pedidos') ?>" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id_producto" value="<?= (int)$product['id_producto'] ?>">
  <label>Cantidad (máximo <?= (int)$product['stock'] ?>)<input type="number" name="cantidad" id="cantidad" min="1" max="<?= min((int)$product['stock'], 50) ?>" value="1" required></label>
  <label>Teléfono<input name="telefono" value="<?= esc(old('telefono')) ?>" maxlength="20" required></label>
  <div class="two-inputs"><label>Provincia<input name="provincia" value="<?= esc(old('provincia')) ?>" maxlength="100" required></label><label>Ciudad o municipio<input name="ciudad" value="<?= esc(old('ciudad')) ?>" maxlength="100" required></label></div>
  <label>Dirección<textarea name="direccion" maxlength="255" required><?= esc(old('direccion')) ?></textarea></label>
  <label>Referencia (opcional)<input name="referencia" value="<?= esc(old('referencia')) ?>" maxlength="255"></label>
  <label>Método de entrega<select name="metodo_entrega" required><option value="delivery">Entrega a domicilio</option><option value="retiro">Retiro en tienda</option></select></label>
  <label>Método de pago<select name="metodo_pago" required><option value="efectivo">Efectivo</option><option value="transferencia">Transferencia (adjuntar comprobante después)</option></select></label>
  <button type="submit" class="btn">Confirmar pedido</button>
 </form>
 </section>
 <aside class="panel order-aside"><p class="eyebrow">Resumen</p>
  <?php if ($product['imagen']): ?><img class="aside-img" src="<?= base_url('img/'.rawurlencode(basename($product['imagen']))) ?>" alt="<?= esc($product['nombre']) ?>"><?php endif ?>
  <h2><?= esc($product['nombre']) ?></h2><p><?= esc($product['descripcion']) ?></p>
  <p>Precio unitario: <strong>RD$ <?= number_format((float)$product['precio'],2) ?></strong></p>
  <p class="small">El total se calcula y comprueba nuevamente en el servidor. No se incluye tarifa de transporte.</p>
 </aside>
</div>
