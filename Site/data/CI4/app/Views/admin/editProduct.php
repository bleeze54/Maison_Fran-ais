<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier produit</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/addproduct.css') ?>">
</head>
<body>

<?php if(session()->getFlashdata('message')): ?>
    <p><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<form action="/admin/products/update" method="post" enctype="multipart/form-data">
    <input type="hidden" name="ID" value="<?= esc($product['id']) ?>">

    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" value="<?= esc($product['nom']) ?>" required>
    <br><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required><?= esc($product['description']) ?></textarea>
    <br><br>

    <label for="prix">Prix :</label>
    <input type="number" step="0.01" name="prix" id="prix" value="<?= esc($product['prix']) ?>" required>
    <br><br>

    <label for="category">Catégorie :</label>
    <select name="category" id="category">
        <?php foreach ($categories as $cat): ?>
            <option value="<?= esc($cat->name) ?>" <?= $product['category'] === $cat->name ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <p>Images actuelles :</p>
    <?php $imgs = json_decode($product['images'] ?? '[]', true) ?: []; ?>
    <?php foreach ($imgs as $key => $img): ?>
        <div style="margin-bottom:10px;">
            <img src="<?= base_url($img) ?>" alt="img" style="max-width:100px; display:block;">
            <label for="replace_image_<?= $key ?>">Nouvelle image (remplace l'actuelle) :</label>
            <input type="file" name="replace_images[<?= $key ?>]" id="replace_image_<?= $key ?>" accept="image/*">
        </div>
    <?php endforeach; ?>

    <br><br>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>