<div class="section-heading"><p class="eyebrow">Gestión de Roséa</p><h1>Panel de administración</h1></div>
<div class="stats">
 <div class="stat"><strong><?= (int)$stats['users'] ?></strong><span>Usuarios</span></div>
 <div class="stat"><strong><?= (int)$stats['products'] ?></strong><span>Productos</span></div>
 <div class="stat"><strong><?= (int)$stats['orders'] ?></strong><span>Pedidos</span></div>
 <div class="stat"><strong><?= (int)$stats['pending'] ?></strong><span>Pendientes</span></div>
</div>
<div class="links-panel"><a class="panel" href="<?= site_url('admin/productos') ?>">✿ Administrar productos →</a><a class="panel" href="<?= site_url('admin/pedidos') ?>">♡ Administrar pedidos →</a><a class="panel" href="<?= site_url('admin/usuarios') ?>">♧ Administrar usuarios →</a></div>
