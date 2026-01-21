<!-- CSS des catégories -->
<link rel="stylesheet" href="<?= base_url('assets/css/categories.css') ?>">

<div class="page_categorie">
  <div class="conteneur_section">

    <h1 class="titre_categorie">Pulls</h1>
    <p class="sous_titre_categorie">
      Confort, chaleur et élégance pour toutes les saisons.
    </p>

    <div class="categorie_grille_produits">
      <?php foreach ($produits as $index => $produit): 
        $images = json_decode($produit['images']);
        $reduction = $reductions[$index];
        $prix = (float) $produit['prix'];
        if ($reduction !== null) {
            $prixReduit = $prix - ($prix * $reduction / 100);
        }
        
  
        $estExclu = ($exclu > $index);
        
      ?>   
    <a href="<?= site_url('produit/' . $produit['id']) ?>" class="categorie_carte_produit">
        <div class="categorie_visuel_produit"
            style="background-image:url('<?= htmlspecialchars($images[0] ?? '') ?>');">
            
            <?php if($estExclu): ?>
                <span class="badge_exclusivite">EXCLU</span>
            <?php endif; ?>
        </div>

        <div class="categorie_info_produit">
            <h3><?= htmlspecialchars($produit['nom']) ?></h3>
                <?php if ($reduction !== null): ?>
                    <div class="categorie_prix_reduc">
                        <span class="prix_ancien">
                            <?= number_format($prix, 0, ',', ' ') ?> €
                        </span>

                        <span class="prix_nouveau">
                            <?= number_format($prixReduit, 0, ',', ' ') ?> €
                        </span>

                        <span class="badge_reduction">
                            -<?= (int)$reduction ?>%
                        </span>
                    </div>
                <?php else: ?>
                    <p class="categorie_prix">
                        <?= number_format($prix, 0, ',', ' ') ?> €
                    </p>
                <?php endif; ?>

                <p class="categorie_desc"><?= htmlspecialchars($produit['description']) ?></p>
                <span class="categorie_btn">Voir le produit</span>
            </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
