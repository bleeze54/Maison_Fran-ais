<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/favoris.css') ?>">
       
</head>
<body>

<div class="container">
    <h1>Mes Produits Favoris</h1>

    <?php if (empty($favoris)): ?>
        <p>Vous n'avez pas encore de favoris. <a href="<?= base_url('/') ?>">Continuer mes achats</a></p>
    <?php else: ?>
        <div class="favoris-grid">
            <?php foreach ($favoris as $item): 
                $images = json_decode($item['images'], true) ?? []; ?>
                <div class="favori-card" id="fav-row-<?= $item['id'] ?>">
                    <button class="remove-fav" onclick="toggleFav(<?= $item['id'] ?>)">×</button>
                    
                    <a href="<?= base_url('produit/' . $item['id']) ?>">
                        <img src="<?= htmlspecialchars($images[0] ?? '') ?>" alt="<?= esc($item['nom']) ?>">
                        <h3><?= esc($item['nom']) ?></h3>
                        <p><?= number_format($item['prix'], 2, ',', ' ') ?> €</p>
                    </a>
                    
                    <a href="<?= base_url('produit/' . $item['id']) ?>" class="bouton_voir">Voir le produit</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleFav(produitId) {
    fetch(`<?= base_url('favori/toggleFavori/') ?>${produitId}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'removed') {
                // Supprime la carte de la page sans recharger
                document.getElementById(`fav-row-${produitId}`).remove();
            }
        });
}
</script>

</body>
</html>