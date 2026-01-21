<!DOCTYPE html>
<html>
<head>
    <title>Produits</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/adminproduct.css') ?>">
</head>
<body>

<main class="content">
    <div class="admin_page_header">
        <h1>Exclusivités</h1>
    </div>
</main>
<details class="categorie_bloc">
    <summary>
        <span class="categorie_nom">exclusivité</span>
        <span class="categorie_count"><?= count($exclusivites) ?> exclusivité(s)</span>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php foreach ($exclusivites as $exclusivite): ?>
            <tr>
                <td><?= $exclusivite['id'] ?></td>
                <td><?= $exclusivite['produit_id'] ?></td>
                <td><?= htmlspecialchars($exclusivite['produit_nom'] ?? 'Inconnu') ?></td>
                <td><?= htmlspecialchars($exclusivite['date_debut']) ?></td>
                <td><?= htmlspecialchars($exclusivite['date_fin']) ?></td>
                <td><?= htmlspecialchars($exclusivite['created_at']) ?></td>
                <td><?= htmlspecialchars($exclusivite['updated_at']) ?></td>
                <td>
                    <form action="<?= base_url('/admin/exclusivites/edit') ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="ID" value="<?= $exclusivite['id'] ?>">
                        <button type="submit">Modifier</button>
                    </form>

                    <form action="<?= base_url('/admin/exclusivites/delete') ?>" method="post" style="display:inline-block;">
                        <input type="hidden" name="ID" value="<?= $exclusivite['id'] ?>">
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
