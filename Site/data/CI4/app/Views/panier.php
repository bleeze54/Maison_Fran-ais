<link rel="stylesheet" href="<?= base_url('assets/css/panier.css') ?>">

<div class="panier_page">
    <div class="panier_card">
        <h2>Votre panier</h2>
        <a href="<?= base_url('/') ?>#categories" class="bouton_continue">← Continuer mes achats</a>

        <?php if (empty($items)): ?>
            <p>Votre panier est vide.</p>
        <?php else: ?>

            <?php foreach ($items as $item): ?>
                <div class="item_panier">
                    <div class="product_preview">
                        <div class="item_image">
                            <img src="<?= base_url($item['image'] ?: 'assets/product/placeholder.png') ?>" alt="<?= esc($item['nom']) ?>">
                        </div>
                        <div>
                            <b><?= esc($item['nom']) ?></b>
                            <div class="infos">
                                Taille : <?= esc($item['taille']) ?><br>
                                Quantité : <?= $item['quantite'] ?>
                            </div>
                        </div>
                    </div>

                    <div class="prix_infos">
                      <?php if ($item['reduction']): ?>
                          <span style="text-decoration: line-through; color: red;">
                              <?= number_format($item['prix_base'], 2, ',', ' ') ?> €
                          </span>
                          <br>
                          <strong><?= number_format($item['prix_final'], 2, ',', ' ') ?> €</strong>
                          <span class="badge_reduction">-<?= $item['reduction'] ?>%</span>
                      <?php else: ?>
                          <strong><?= number_format($item['prix_base'], 2, ',', ' ') ?> €</strong>
                      <?php endif; ?>

                      <div class="sous_total">
                          Sous-total : <?= number_format($item['sous_total'], 2, ',', ' ') ?> €
                      </div>
                  </div>

                    <div class="actions">
                        <a href="<?= site_url('panier/increment/'.$item['id'].'/'.$item['taille']) ?>">+1</a>
                        <a href="<?= site_url('panier/decrement/'.$item['id'].'/'.$item['taille']) ?>">-1</a>
                        <a href="<?= site_url('panier/remove/'.$item['id'].'/'.$item['taille']) ?>" class="delete">Supprimer</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="total_panier">
                Total : <strong><?= number_format($total, 2, ',', ' ') ?> €</strong>
            </div>

            <a href="<?= site_url('panier/processcheckout') ?>" class="bouton_commander"> Commander </a>


        <?php endif; ?>
    </div>
</div>