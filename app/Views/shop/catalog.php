<section class="hero">
  <p class="eyebrow">Una colección creada para ti</p>
  <h1>Tu esencia, tu estilo <span>Roséa</span></h1>
  <p>Prendas románticas y detalles que hacen especial cada día.</p>
  <a class="btn outline" href="#coleccion">Explorar colección ↓</a>
</section>
<section id="coleccion" class="section">
 <div class="section-heading"><p class="eyebrow">Nuestra selección</p><h2>Descubre la colección</h2></div>
 <?php if (empty($products)): ?><div class="panel">Todavía no hay productos disponibles.</div><?php endif ?>
 <div class="product-grid">
 <?php foreach ($products as $p): ?>
   <article class="product-card">
    <div class="photo">
     <?php if ($p['imagen']): ?><img src="<?= base_url('img/'.rawurlencode(basename($p['imagen']))) ?>" alt="<?= esc($p['nombre']) ?>"><?php else: ?><div class="placeholder">♡</div><?php endif ?>
     <span class="stock-badge"><?= (int)$p['stock'] > 0 ? 'Disponible' : 'Agotado' ?></span>
    </div>
    <div class="product-body">
      <p class="eyebrow"><?= esc($p['categoria']) ?></p><h3><?= esc($p['nombre']) ?></h3>
      <p><?= esc($p['descripcion']) ?></p>
      <p class="price">RD$ <?= number_format((float)$p['precio'],2) ?></p>
      <?php if ((int)$p['stock'] > 0): ?>
      <div class="row-between"><span class="small">Quedan <?= (int)$p['stock'] ?> unidades</span><a class="btn" href="<?= site_url('checkout/'.$p['id_producto']) ?>">Comprar</a></div>
      <?php else: ?><button class="btn" disabled>Agotado</button><?php endif ?>
    </div>
   </article>
 <?php endforeach ?>
 </div>
</section>
