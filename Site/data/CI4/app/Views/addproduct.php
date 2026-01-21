<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/addproduct.css') ?>">
</head>
<body>

<?php if(session()->getFlashdata('message')): ?>
    <p><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>
<form action="/admin/products/store" method="post" enctype="multipart/form-data">
    <label for="nom">Nom :</label>
    <input type="text" name="nom" id="nom" required><br><br>

    <label for="prix">Prix (€) :</label>
    <input type="number" name="prix" step="0.01" id="prix" required><br><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description"></textarea><br><br>

    <label>Quantités par taille :</label>
    <div>
        <label>S: <input type="number" name="quantite[S]" value="0" min="0"></label>
        <label>M: <input type="number" name="quantite[M]" value="0" min="0"></label>
        <label>L: <input type="number" name="quantite[L]" value="0" min="0"></label>
        <label>XL: <input type="number" name="quantite[XL]" value="0" min="0"></label>
        <label>XXL: <input type="number" name="quantite[XXL]" value="0" min="0"></label>
    </div><br>

    <label for="category">Catégorie :</label>
    <select name="category" id="category" required>
        <option value="">--Choisir--</option>
        <?php foreach($categories as $cat): ?>
            <option value="<?= $cat->name ?>"><?= $cat->value ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="image">Image :</label>
    <input type="file" name="images[]" id="images" multiple accept="image/*"><br><br>

    <button type="submit">Ajouter le produit</button>
</form>

</body>
</html>
