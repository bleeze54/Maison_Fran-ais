<main>
  <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="background-color: #ffe6e6; color: #cc0000; padding: 15px; border-radius: 8px; border: 1px solid #cc0000; margin-bottom: 20px; font-family: sans-serif;">
                <strong>Erreur :</strong> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
  <link rel="stylesheet" href="/assets/css/accueil.css">
    <section id="accueil" class="section_hero">
        <div class="banniere_image">
            <div class="banniere_overlay"></div>
            <video class="banniere_video" autoplay muted loop playsinline preload="none">
                <source src="/assets/picture/banniere.mp4" type="video/mp4">
            </video>

            <div class="banniere_contenu">
                <h1 class="titre_principal">Style durable, esprit français</h1>
                <p class="sous_titre">Collections responsables — livraison France & UE</p>

                <div class="cta_group">
                    <a href="<?= base_url('/') ?>#produits_mise_en_avant" class="bouton_principal bouton_large">Découvrir</a>
                    <a href="<?= base_url('/') ?>#presentation" class="bouton_secondaire">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>


    <!-- SECTION PRÉSENTATION -->
    <section id="presentation" class="section_presentation">
        <div class="conteneur_section">

          <h2 class="titre_section">Notre engagement</h2>

          <p class="texte_intro_presentation">
            Depuis sa création, Maison Française s’engage à concevoir des vêtements durables,
            responsables et élégants, fabriqués avec des matériaux sélectionnés pour leur qualité.
          </p>

          <div class="bloc_presentation quinconce">
              <div class="bloc_image">
                <img src="assets/picture/presentation/collection-de-colorants-organiques-vue-de-dessus.jpg">
              </div>
              <div class="bloc_texte">
                <h3>Des matières sélectionnées</h3>
                <p>Nous travaillons avec des ateliers partenaires...</p>
              </div>
          </div>

          <div class="bloc_presentation quinconce inverse">
              <div class="bloc_image">
                <img src="assets/picture/presentation/femme-coupe-textile-pour-coudre-design.jpg">
              </div>
              <div class="bloc_texte">
                <h3>Une confection soignée</h3>
                <p>Nos pièces sont conçues avec une attention particulière...</p>
              </div>
          </div>

          <div class="bloc_presentation quinconce">
              <div class="bloc_image">
                <img src="assets/picture/presentation/gros-plan-sur-des-trucs-d-organisation-de-benevoles-pour-un-don.jpg">
              </div>
              <div class="bloc_texte">
                <h3>Un engagement éthique</h3>
                <p>Maison Française défend une mode plus juste...</p>
              </div>
          </div>

        </div>
    </section>


    <hr>


    <!-- SECTION EXCLUSIVITÉS -->
    <!-- SECTION EXCLUSIVITÉS -->
<section id="produits_mise_en_avant" class="section_featured">
  <div class="conteneur_section">

    <div class="featured_header">
      <h2 class="titre_section">Sélection Exclusives</h2>
      <p class="featured_subtitle">
        Nos pièces incontournables du moment — qualité, élégance et savoir-faire français.
      </p>
    </div>

    <div class="featured_carousel">

      <?php foreach ($produits as $produitIndex => $produit): 
        $images = json_decode($produit['images'], true);
        $image = $images[0] ?? '';
        $reduction = $reductions[$produitIndex] ?? null;
        $prix = (float) $produit['prix'];
        if ($reduction !== null) {
            $prixReduit = $prix - ($prix * $reduction / 100);
        }

      ?>

        <a href="<?= site_url('produit/' . $produit['id']) ?>" class="featured_link">

          <div class="featured_card">

            <div class="featured_img"
              style="background-image:url('<?= htmlspecialchars($image) ?>');">
            </div>

            <?php if (!empty($produit['badge'])): ?>
              <span class="featured_tag <?= $produit['badge'] === 'Nouveau' ? 'new' : '' ?>">
                <?= htmlspecialchars($produit['badge']) ?>
              </span>
            <?php endif; ?>

            <div class="featured_info">
              <h3><?= htmlspecialchars($produit['nom']) ?></h3>
              <p class="prix">
                <?php if ($reduction !== null): ?>
                    <span style="text-decoration: line-through; color: red;">
                        <?= number_format($prix, 0, ',', ' ') ?> €
                    </span>
                    <br>
                    <strong><?= number_format($prixReduit, 0, ',', ' ') ?> €</strong>
                    <span class="badge_reduction">-<?= $reduction ?>%</span>
                <?php else: ?>
                    <?= number_format($prix, 0, ',', ' ') ?> €
                <?php endif; ?>

              </p>
              <span class="btn_featured">Voir le produit</span>
            </div>

          </div>
        </a>

      <?php endforeach; ?>

    </div>
  </div>
</section>



    <hr>


    <!-- SECTION CATEGORIES -->
    <section id="categories" class="section_categories">
      <div class="conteneur_section">
        <h2 class="titre_section">Catégories</h2>

        <div class="grille_categories">

          <a class="carte_categorie" href="<?= base_url('pantalons') ?>">
            <div class="image_categorie" style="background-image:url('assets/picture/categories/Pantalon.jpg');"></div>
            <div class="info_categorie">
              <h3>Pantalons</h3>
              <p>Coupe moderne et tissus sélectionnés</p>
            </div>
          </a>

          <a class="carte_categorie" href="<?= base_url('tshirts') ?>">
            <div class="image_categorie" style="background-image:url('assets/picture/categories/Tee_shirt.jpg');"></div>
            <div class="info_categorie">
              <h3>T-shirts</h3>
              <p>Basiques confortables et motifs soignés</p>
            </div>
          </a>

          <a class="carte_categorie" href="<?= base_url('pulls') ?>">
            <div class="image_categorie" style="background-image:url('assets/picture/categories/pull.jpg');"></div>
            <div class="info_categorie">
              <h3>Pulls</h3>
              <p>Textiles doux pour saisons fraîches</p>
            </div>
          </a>

          <a class="carte_categorie" href="<?= base_url('accessoires') ?>">
            <div class="image_categorie" style="background-image:url('assets/picture/categories/accessoire.jpg');"></div>
            <div class="info_categorie">
              <h3>Accessoires</h3>
              <p>Finitions et détails essentiels</p>
            </div>
          </a>

        </div>
      </div>
    </section>
</main>
