<link rel="stylesheet" href="<?= base_url('assets/css/produit.css') ?>">

<div class="single_produit">
    <div class="left_img">
        <img src="<?= base_url($images[0] ?? '') ?>" alt="">
    </div>

    <div class="right_info">
        <a href="<?= base_url('/' . strtolower($produit['category']) . 's') ?>" class="bouton_retour">← Retour</a>
        <h2><?= esc($produit['nom']) ?></h2>
        <p><?= esc($produit['description']) ?></p>
        <?php if ($reduction !== null): ?>
        <h3>
            <span style="text-decoration: line-through; color:#999;">
                <?= number_format($produit['prix'], 0, ',', ' ') ?> €
            </span>
            <span style="color:red; font-weight:bold;">
                <?= number_format($prixReduit, 0, ',', ' ') ?> €
            </span>
            <span class="badge_reduction">-<?= $reduction ?>%</span>
        </h3>
    <?php else: ?>
        <h3>Prix: <?= number_format($produit['prix'], 0, ',', ' ') ?> €</h3>
    <?php endif; ?>
        <?php
        $totalStock = array_sum(array_column($sizes, 'quantite'));
        ?>
        <h4>
            Mettre en Favori: 
            <span id="btn-favori" 
                data-id="<?= $produit['id'] ?>" 
                style="cursor:pointer; font-size: 24px; color: <?= $isFavori ? 'red' : '#ccc' ?>;">
                ❤
            </span>
        </h4>
        <h3>Reste : <?= $totalStock ?> Produit(s)</h3>
        <h4>Quantité et taille :</h4>
        <form action="<?= site_url('panier/add/' . $produit['id']) ?>" method="post">
            <label for="taille">Taille:</label>
            <select name="taille" id="taille" required>
                <?php if (!empty($sizes)): ?>
                    <?php foreach ($sizes as $s): ?>
                        <option value="<?= $s['taille'] ?>"><?= $s['taille'] ?> (<?= $s['quantite'] ?> dispo)</option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>Produit en rupture de stock</option>
                <?php endif; ?>
            </select>
            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" min="1" value="1">
            <button type="submit">Ajouter au panier</button>
        </form>
    </div>
    
</div>

<script>document.getElementById('btn-favori').addEventListener('click', function() {
    const produitId = this.getAttribute('data-id');
    const btn = this;

    fetch(`<?= base_url('favori/toggleFavori/') ?>${produitId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                btn.style.color = 'red';
            } else if (data.status === 'removed') {
                btn.style.color = '#ccc';
            } else {
                alert(data.message);
            }
        });
});</script>