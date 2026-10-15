<p class="eyebrow"><a href="<?= site_url('admin/pedidos') ?>">← Volver a pedidos</a></p><h1>Gestión de pedido #<?= (int)$order['id_pedido'] ?></h1>
<div class="two-col">
<section class="panel"><h2>Información de compra</h2>
<p><strong>Cliente:</strong> <?= esc($order['nombre'].' '.$order['apellido']) ?> (<?= esc($order['email']) ?>)</p>
<p><strong>Entrega:</strong> <?= esc($order['metodo_entrega']) ?></p><p><strong>Dirección:</strong> <?= esc($order['provincia'].', '.$order['ciudad'].', '.$order['direccion']) ?></p>
<p><strong>Teléfono:</strong> <?= esc($order['telefono']) ?></p><p><strong>Método de pago:</strong> <?= esc($order['metodo_pago']) ?></p>
<?php if ($order['comprobante_pago']): ?><a href="<?= site_url('pedidos/'.$order['id_pedido'].'/comprobante') ?>">Descargar comprobante privado</a><?php endif ?>
<div class="table-wrap"><table><thead><tr><th>Producto</th><th>Cantidad</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach ($details as $item): ?><tr><td><?= esc($item['nombre']) ?></td><td><?= (int)$item['cantidad'] ?></td><td>RD$ <?= number_format((float)$item['subtotal'],2) ?></td></tr><?php endforeach ?></tbody></table></div>
<h2>Total: RD$ <?= number_format((float)$order['total'],2) ?></h2></section>
<aside class="panel"><h2>Actualizar estado</h2><p>Estado actual: <span class="tag"><?= esc($order['estado']) ?></span></p>
<form class="stack" method="post" action="<?= site_url('admin/pedidos/'.$order['id_pedido'].'/estado') ?>"><?= csrf_field() ?>
<label>Estado del pedido<select name="estado">
<?php foreach(['pendiente','procesando','enviado','entregado','cancelado'] as $s): ?><option value="<?= $s ?>" <?= $order['estado'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach ?>
</select></label><button class="btn">Actualizar estado</button></form>
<h2>Validar pago</h2><p>Estado de pago: <span class="tag"><?= esc($order['estado_pago']) ?></span></p>
<form class="stack" method="post" action="<?= site_url('admin/pedidos/'.$order['id_pedido'].'/pago') ?>"><?= csrf_field() ?>
<label>Pago<select name="estado_pago">
<?php foreach(['pendiente','verificado','rechazado'] as $s): ?><option value="<?= $s ?>" <?= $order['estado_pago']===$s ? 'selected':'' ?>><?= ucfirst($s) ?></option><?php endforeach ?>
</select></label><button class="btn outline">Guardar estado de pago</button></form>
<?php if ($order['codigo_seguimiento']): ?><p><strong>Seguimiento:</strong> <?= esc($order['codigo_seguimiento']) ?></p><?php endif ?>
</aside></div>
