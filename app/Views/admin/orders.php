<p class="eyebrow"><a href="<?= site_url('admin') ?>">← Panel de control</a></p><h1>Administrar pedidos</h1>
<div class="table-wrap"><table><thead><tr><th>ID</th><th>Cliente</th><th>Estado</th><th>Pago</th><th>Total</th><th>Detalle</th></tr></thead><tbody>
<?php foreach($orders as $o): ?><tr><td>#<?= (int)$o['id_pedido'] ?></td><td><?= esc($o['nombre'].' '.$o['apellido']) ?><span class="small block"><?= esc($o['email']) ?></span></td><td><?= esc($o['estado']) ?></td><td><?= esc($o['estado_pago']) ?></td><td>RD$ <?= number_format((float)$o['total'],2) ?></td><td><a href="<?= site_url('admin/pedidos/'.$o['id_pedido']) ?>">Gestionar →</a></td></tr><?php endforeach ?>
</tbody></table></div>
