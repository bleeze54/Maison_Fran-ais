<link rel="stylesheet" href="<?= base_url('assets/css/infocommande.css') ?>">

<div class="infocommande_page">
    <div class="infocommande_card">
        <h2>Récapitulatif commande</h2>

        <?php foreach ($items as $item): ?>
            <div class="item_commande">
                <div class="product_preview">
                    <div class="item_image">
                        <img src="<?= base_url($item['image']) ?>" alt="<?= htmlspecialchars($item['nom']) ?>">
                    </div>
                    <div class="product_details">
                        <p class="item_name"><?= htmlspecialchars($item['nom']) ?></p>
                        <small>Taille : <?= htmlspecialchars($item['taille']) ?></small>
                        
                        <?php if ($item['a_reduction']): ?>
                            <span class="badge_reduc" style="background: #e74c3c; color: white; padding: 2px 5px; border-radius: 3px; font-size: 0.7em; display: inline-block; margin-top: 5px;">
                                -<?= $item['taux_reduction'] ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="infos">
                    <?= $item['quantite'] ?> × 
                    <?php if ($item['a_reduction']): ?>
                        <span style="text-decoration: line-through; color: #999; margin-right: 5px; font-size: 0.9em;">
                            <?= number_format($item['prix_base'], 2, ',', ' ') ?>€
                        </span>
                    <?php endif; ?>
                    <strong><?= number_format($item['prix_unit'], 2, ',', ' ') ?> €</strong>
                </div>

                <div class="prix_infos">
                    <strong><?= number_format($item['sous_total'], 2, ',', ' ') ?> €</strong>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="total_section" style="margin-top: 20px; border-top: 2px solid #eee; padding-top: 15px; text-align: right;">
            <strong class="total_commande" style="font-size: 1.4em;">Total : <?= number_format($total, 2, ',', ' ') ?> €</strong>
        </div>

        <form class="commande_form" method="post" action="<?= site_url('commande/confirm') ?>" style="margin-top: 20px;">
    <?= csrf_field() ?>

    <div class="form_group" style="margin-bottom: 10px;">
        <input type="text" name="address_street" placeholder="Adresse (N° et rue)" style="width: 100%; padding: 10px;" required>
    </div>
    
    <div class="form_group" style="display: flex; gap: 10px; margin-bottom: 10px;">
        <input type="text" name="address_zip" placeholder="Code Postal" maxlength="5" style="flex: 1; padding: 10px;" required>
        <input type="text" name="address_city" placeholder="Ville" style="flex: 2; padding: 10px;" required>
    </div>
    
    <div class="form_group" style="display: flex; gap: 10px; margin-bottom: 10px;">
        <input type="text" name="card" placeholder="N° de Carte" maxlength="16" style="flex: 2; padding: 10px;" required>
        <input type="text" name="crypto" placeholder="CVV" maxlength="3" style="flex: 1; padding: 10px;" required>
    </div>

            <?php if ($total >= 100): ?>
                <p class="info_livraison" style="color: #27ae60; font-weight: bold; margin-top: 15px; font-size: 0.9em;">
                    ✨ Félicitations ! Livraison prioritaire gratuite.
                </p>
            <?php endif; ?>

            <div class="form_group" style="margin-top: 15px;">
                <label for="priority" style="display: block; margin-bottom: 5px; font-size: 0.8em; color: #666;">Mode de livraison :</label>
                <select name="priority" id="priority" style="width: 100%; padding: 8px;">
                    <?php if ($total >= 100): ?>
                        <option value="prioritaire">Prioritaire (Offert)</option>
                    <?php else: ?>
                        <option value="standard">Standard</option>
                    <?php endif; ?>
                    <option value="point_relais">Point relais</option>
                </select>
            </div>

            <button type="submit" class="bouton_commander" style="width: 100%; margin-top: 20px; padding: 12px; background-color: #333; color: white; border: none; cursor: pointer; font-weight: bold; border-radius: 4px;">
                Confirmer la commande
            </button>
        </form>
    </div>
</div>