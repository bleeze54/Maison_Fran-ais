<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier <?php echo htmlspecialchars($type) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/addproduct.css') ?>">
</head>
<body>

<?php if(session()->getFlashdata('message')): ?>
    <p><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>

<form action="/admin/<?php echo($type); ?>s/update" method="post">
    <input type="hidden" name="ID" value="<?= esc($event['id']) ?>">
    <p>Produit: <?= htmlspecialchars($event['produit_nom'] ?? 'Inconnu') ?> (ID: <?= esc($event['produit_id']) ?>)</p>

    <label for="date_debut">Date de début :</label>
    <input type="date" name="date_debut" id="date_debut" value="<?= esc($event['date_debut']) ?>" required>
    <br><br>

    <label for="date_fin">Date de fin :</label>
    <input type="date" name="date_fin" id="date_fin" value="<?= esc($event['date_fin']) ?>" required>
    <br><br>

    <?php if ($type === 'reduction'): ?>
        <label for="pourcentage_reduction">Pourcentage de réduction :</label>
        <input type="number" name="pourcentage_reduction" id="pourcentage_reduction" min="0" max="100" value="<?= esc($event['pourcentage_reduction'] ?? '') ?>" required>
        <br><br>
    <?php endif; ?>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>