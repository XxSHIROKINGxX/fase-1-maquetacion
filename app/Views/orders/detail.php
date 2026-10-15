<p class="eyebrow"><a href="<?= site_url('perfil') ?>">← Volver a mi perfil</a></p><h1>Pedido #<?= (int)$order['id_pedido'] ?></h1>
<div class="panel"><div class="row-between"><p>Estado: <span class="tag"><?= esc($order['estado']) ?></span></p><p>Pago: <span class="tag"><?= esc($order['estado_pago']) ?></span></p></div>
<p>Fecha: <?= esc($order['fecha_pedido']) ?> · Método: <?= esc($order['metodo_pago']) ?></p>
<p>Entrega: <?= esc($order['metodo_entrega']) ?> · Dirección: <?= esc($order['provincia'].', '.$order['ciudad'].', '.$order['direccion']) ?></p>
<?php if ($order['codigo_seguimiento']): ?><p>Seguimiento: <strong><?= esc($order['codigo_seguimiento']) ?></strong></p><?php endif ?>
<h2>Artículos</h2><div class="table-wrap"><table><thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($details as $item): ?><tr><td><?= esc($item['nombre']) ?></td><td><?= (int)$item['cantidad'] ?></td><td>RD$ <?= number_format((float)$item['precio_unitario'],2) ?></td><td>RD$ <?= number_format((float)$item['subtotal'],2) ?></td></tr><?php endforeach ?>
</tbody></table></div><h2 class="right">Total: RD$ <?= number_format((float)$order['total'],2) ?></h2></div>
<?php if ($order['comprobante_pago']): ?><p><a href="<?= site_url('pedidos/'.$order['id_pedido'].'/comprobante') ?>">Descargar comprobante registrado</a></p><?php endif ?>
<?php if ($order['metodo_pago'] === 'transferencia' && $order['estado'] !== 'cancelado'): ?>
<section class="panel narrow"><h2>Adjuntar comprobante de transferencia</h2><p>Archivos permitidos: JPG, PNG o PDF (máximo 3 MB).</p>
<form class="stack" enctype="multipart/form-data" method="post" action="<?= site_url('pedidos/'.$order['id_pedido'].'/comprobante') ?>">
<?= csrf_field() ?><input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required><button class="btn">Enviar comprobante</button></form></section>
<?php endif ?>
