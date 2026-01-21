<!DOCTYPE html>
<html>
<head>
    <title>Tailles produits</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/sizes.css') ?>">
</head>
<body>

<main class="content">
    <h1>Tailles produits</h1>

    <?php if(session()->getFlashdata('message')): ?>
        <p><?= session()->getFlashdata('message') ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>S</th>
                <th>M</th>
                <th>L</th>
                <th>XL</th>
                <th>XXL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
                <tr>
                    <td><?= esc($product['id']) ?></td>
                    <td><?= esc($product['nom']) ?></td>
                    <td><?= esc($product['sizes']['S']) ?></td>
                    <td><?= esc($product['sizes']['M']) ?></td>
                    <td><?= esc($product['sizes']['L']) ?></td>
                    <td><?= esc($product['sizes']['XL']) ?></td>
                    <td><?= esc($product['sizes']['XXL']) ?></td>
                    <td>
                        <a href="<?= base_url('/admin/tailles/edit/' . $product['id']) ?>">Modifier</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</main>

</body>
</html>
