<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/adminproduct.css') ?>">
</head>
<body>

<main class="content">
</main>
<details class="categorie_bloc">
    <summary>
        <span class="categorie_nom">token de connexion</span>
        <span class="categorie_count"><?= count($tokens) ?> token(s)</span>
    </summary>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID user</th>
                <th>Hash</th>
                <th>Date Fin</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($tokens as $token): ?>
            <tr>
                <td><?= $token['id'] ?></td>
                <td><?= $token['user_id'] ?></td>
                <td><?= htmlspecialchars($token['token_hash']) ?></td>
                <td><?= $token['expires_at'] ?> </td>
                <td><?= htmlspecialchars($token['created_at']) ?></td>
                <td>
                    <form action="<?= base_url('/admin/usertoken/delete') ?>" method="post">
                        <input type="hidden" name="ID" value="<?= $token['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</details>

</body>
</html>
