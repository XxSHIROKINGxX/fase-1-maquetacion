<div class="section-heading"><p class="eyebrow">Tu rincón Roséa</p><h1>Mi perfil</h1></div>
<div class="panel"><h2>¡Hola, <?= esc($user['nombre']) ?>!</h2><p><?= esc($user['nombre'].' '.$user['apellido']) ?> · <?= esc($user['email']) ?></p><p class="small">Tipo de cuenta: <?= esc($user['rol']) ?></p></div>
<section class="section"><h2>Mis pedidos</h2>
 <?php if (!$orders): ?><div class="panel">Aún no has comprado. <a href="<?= site_url('productos') ?>">Explorar colección</a></div><?php else: ?>
 <div class="table-wrap"><table><thead><tr><th>Pedido</th><th>Fecha</th><th>Estado</th><th>Total</th><th></th></tr></thead><tbody>
 <?php foreach ($orders as $p): ?><tr><td>#<?= (int)$p['id_pedido'] ?></td><td><?= esc($p['fecha_pedido']) ?></td><td><span class="tag"><?= esc($p['estado']) ?></span></td><td>RD$ <?= number_format((float)$p['total'],2) ?></td><td><a href="<?= site_url('pedidos/'.$p['id_pedido']) ?>">Detalles →</a></td></tr><?php endforeach ?>
 </tbody></table></div><?php endif ?>
</section>
