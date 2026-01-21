<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/adminproduct.css') ?>">
</head>
<body>

<main class="content">
    <div class="admin_page_header">
        <h1>Produits</h1>
        <a href="<?= base_url('/admin/products/create') ?>" class="btn_add_product">Ajouter produit</a>
    </div>
</main>





<?php foreach ($produitsParCategorie as $categorie => $produits): ?>

<details class="categorie_bloc">
    <summary>
        <span class="categorie_nom"><?= htmlspecialchars($categorie) ?></span>
        <span class="categorie_count"><?= count($produits) ?> produit(s)</span>
    </summary>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Description</th>
                <th>Images</th>
                <th>Quantité</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= $produit['id'] ?></td>
                <td><?= htmlspecialchars($produit['nom']) ?></td>
                <td><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                <td><?= htmlspecialchars($produit['description']) ?></td>
                <td><?= htmlspecialchars($produit['images']) ?></td>
                <td><?= $produit['quantite'] ?></td>
                <td>
                    <form action="<?= base_url('/admin/products/delete') ?>" method="post">
                        <input type="hidden" name="ID" value="<?= $produit['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                    <form action="<?= base_url('/admin/products/edit') ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="ID" value="<?= $produit['id'] ?>">
                        <button type="submit">Modifier</button>
                    </form>
                    <form action="<?= base_url('/admin/exclusivites/create') ?>" method="post">
                        <input type="hidden" name="ID" value="<?= $produit['id'] ?>">
                        <button type="submit">ajouter en Exclusivite</button>
                    </form>
                    <form action="<?= base_url('/admin/reductions/create') ?>" method="post">
                        <input type="hidden" name="ID" value="<?= $produit['id'] ?>">
                        <button type="submit">ajouter en Reduction</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</details>

<?php endforeach; ?>

</body>
</html>
