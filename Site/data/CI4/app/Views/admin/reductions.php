<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/adminproduct.css') ?>">
</head>
<body>

<main class="content">
    <div class="admin_page_header">
        <h1>Reductions</h1>
    </div>
</main>
<details class="categorie_bloc">
    <summary>
        <span class="categorie_nom">reduction</span>
        <span class="categorie_count"><?= count($reductions) ?> reduction(s)</span>
    </summary>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit ID</th>
                <th>Nom du produit</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Reduction</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($reductions as $reduction): ?>
            <tr>
                <td><?= $reduction['id'] ?></td>
                <td><?= $reduction['produit_id'] ?></td>
                <td><?= htmlspecialchars($reduction['produit_nom'] ?? 'Inconnu') ?></td>
                <td><?= htmlspecialchars($reduction['date_debut']) ?></td>
                <td><?= htmlspecialchars($reduction['date_fin']) ?></td>
                <td><?= htmlspecialchars($reduction['updated_at']) ?></td>
                <td><?= htmlspecialchars($reduction['updated_at']) ?></td>
                <td><?= htmlspecialchars($reduction['pourcentage_reduction']) ?></td>

                <td>
                    <form action="<?= base_url('/admin/reductions/edit') ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="ID" value="<?= $reduction['id'] ?>">
                        <button type="submit">Modifier</button>
                    </form>

                    <form action="<?= base_url('/admin/reductions/delete') ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="ID" value="<?= $reduction['id'] ?>">
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
