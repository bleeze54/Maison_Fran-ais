<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une <?php echo($type); ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/addproduct.css') ?>">
</head>
<body>

<?php if(session()->getFlashdata('message')): ?>
    <p><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<form action='/admin/<?php echo($type); ?>s/store' method="post" enctype="multipart/form-data">
    <label for="idproduct">id du produit :</label>
    <?php if(is_null($userID)): ?>
    <label for="idproduct">id du produit :</label>
    <input type="number" name="idproduct" id="idproduct" required>
    <?php else: ?>

    <label for="idproduct">id du produit :</label>
    <input type="number" name="idproduct" id="idproduct" value="<?= esc($userID) ?>" readonly>

    <?php endif ?>
    <br><br>
    <label for="pourcentage_reduction">montant de la reduction :</label>
    <?php if($type == 'reduction'): ?>
    <input type="number" name="pourcentage_reduction" id="pourcentage_reduction" min="5" max="80"  placeholder="5 à 80 %" required >
    <br><br>
    <?php endif ?>
    <label for="date_debut">Date de début :</label>
    <input type="date" name="date_debut" id="date_debut" required>
    <br><br>

    <label for="date_fin">Date de fin :</label>
    <input type="date" name="date_fin" id="date_fin" required>
    <br><br>
    <button type="submit">Ajouter <?php echo($type); ?></button>

</form>

</body>
</html>
