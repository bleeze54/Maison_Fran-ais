<link rel="stylesheet" href="<?= base_url('assets/css/commande.css') ?>">

<div class="commande_container">
    <h2 class="titre_page">Mes commandes</h2>

    <?php if (!empty($orders)): ?>
        <div class="orders_grid">
            <?php foreach ($orders as $order): ?>
                <?php 
                    // Définition de la classe de couleur selon le statut
                    $statusClass = ($order['status'] === 'livree') ? 'status-vert' : (($order['status'] === 'en_attente') ? 'status-orange' : 'status-gris');
                ?>
                <div class="order_card order_card--stack">
                    <div class="order_header">
                        <span class="order_id">Commande #<?= $order['id'] ?></span>
                        <span class="order_status <?= $statusClass ?>">
                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $order['status']))) ?>
                        </span>
                    </div>

                    <div class="order_body" style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                        <div>
                            <p class="order_price" style="margin:0;"><?= number_format($order['total_price'], 2) ?> €</p>
                            <p class="order_date" style="margin:0; font-size:.9em; color:#666;">Passée le : <?= date('d/m/Y', strtotime($order['created_at'] ?? 'now')) ?></p>
                        </div>
                        <button type="button" class="order_toggle" onclick="toggleOrder(<?= $order['id'] ?>)" aria-controls="details-<?= $order['id'] ?>" aria-expanded="false" style="background:none;border:none;cursor:pointer;font-size:1.2em;">
                            <span id="arrow-<?= $order['id'] ?>">détails▸</span>
                        </button>
                    </div>

                    <div id="details-<?= $order['id'] ?>" class="order_details" style="display:none; margin-top:10px;">
                        <?php if (!empty($order['items_detail'])): ?>
                            <?php foreach ($order['items_detail'] as $it): ?>
                                <div class="order_product_row" style="display:flex; align-items:center; gap:12px; padding:8px 0; border-top:1px solid #eee;">
                                    <img src="<?= base_url($it['image']) ?>" alt="<?= esc($it['nom']) ?>" style="width:48px; height:48px; object-fit:cover; border-radius:6px;">
                                    <div style="flex:1;">
                                        <div class="prod_name" style="font-weight:600;"><?= esc($it['nom']) ?></div>
                                        <small style="color:#555;">Taille : <?= esc($it['size'] ?? '-') ?> · Qté : <?= (int)$it['quantity'] ?></small>
                                        <div style="margin-top:6px;">
                                            <?php if (!empty($it['a_reduction'])): ?>
                                                <span style="text-decoration: line-through; color:#999; font-size:.9em; margin-right:6px;">
                                                    <?= number_format($it['prix_base'], 2, ',', ' ') ?> €
                                                </span>
                                                <strong style="margin-right:6px;">
                                                    <?= number_format($it['prix_unit'], 2, ',', ' ') ?> €
                                                </strong>
                                                <span style="background:#e74c3c; color:#fff; border-radius:4px; padding:2px 6px; font-size:.75em;">
                                                    -<?= (int)$it['taux_reduction'] ?>%
                                                </span>
                                            <?php else: ?>
                                                <strong><?= number_format($it['prix_unit'], 2, ',', ' ') ?> €</strong>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="prod_price" style="font-weight:600;">
                                        <?= number_format($it['sous_total'], 2, ',', ' ') ?> €
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="padding:8px 0; border-top:1px solid #eee;"><em>Aucun détail disponible.</em></div>
                        <?php endif; ?>
                    </div>

                    <?php if ($order['status'] === 'en_attente'): ?>
                        <div class="order_footer">
                            <form method="post" action="<?= site_url('commande/annuler/'.$order['id']) ?>">
                                <?= csrf_field() ?>
                                <button type="submit" class="bouton_annuler">Annuler</button>
                            </form> 
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="aucune_commande">
            <p>Vous n'avez pas encore passé de commande.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleOrder(id) {
  const details = document.getElementById('details-' + id);
  const arrow = document.getElementById('arrow-' + id);
  const open = details && details.style.display === 'block';
  if (details) details.style.display = open ? 'none' : 'block';
  if (arrow) arrow.textContent = open ? 'détails ▸' : 'détails ▾';
}
</script>