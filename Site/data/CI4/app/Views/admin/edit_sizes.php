<!DOCTYPE html>
<html>
<head>
    <title>Modifier tailles</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/edit_sizes.css') ?>">
</head>
<body>

<main class="content">
    <h1>Modifier tailles pour <?= esc($product['nom']) ?></h1>

    <form action="<?= base_url('/admin/tailles/update') ?>" method="post">
        <input type="hidden" name="product_id" value="<?= esc($product['id']) ?>">
        <label>S</label>
        <input type="number" name="S" value="<?= esc($quantities['S']) ?>" min="0">
        <label>M</label>
        <input type="number" name="M" value="<?= esc($quantities['M']) ?>" min="0">
        <label>L</label>
        <input type="number" name="L" value="<?= esc($quantities['L']) ?>" min="0">
        <label>XL</label>
        <input type="number" name="XL" value="<?= esc($quantities['XL']) ?>" min="0">
        <label>XXL</label>
        <input type="number" name="XXL" value="<?= esc($quantities['XXL']) ?>" min="0">
        <button type="submit">Enregistrer</button>
    </form>

</main>

</body>
</html>
