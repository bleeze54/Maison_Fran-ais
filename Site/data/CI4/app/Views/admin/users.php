<!DOCTYPE html>
<html>
<head>
    <title>Gestion des utilisateurs</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/adminproduct.css') ?>">
</head>
<body>

<main class="content">
    <div class="admin_page_header">
        <h1>Gestion des utilisateurs</h1>
    </div>

    <?php if(session()->getFlashdata('message')): ?>
    <p><?= session()->getFlashdata('message') ?></p>
<?php endif; ?>
    <?php 
    // Diviser en deux sections: Admins et Non-Admins
    $roles = ['admin' => 'Utilisateurs Admins', 'nonAdmin' => 'Utilisateurs Non-Admins'];
    foreach ($roles as $roleKey => $roleTitle): ?>
        <details class="categorie_bloc">
            <summary>
                <span class="categorie_nom"><?= $roleTitle ?></span>
                <span class="categorie_count"><?= count(array_filter($allUsers, fn($user) => $user['role'] === $roleKey)) ?> <?= strtolower($roleTitle) ?></span>
            </summary>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Date de creation</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allUsers as $user): ?>
                        <?php if ($user['role'] === $roleKey): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= esc($user['prenom']) ?></td>
                                <td><?= esc($user['nom']) ?></td>
                                <td><?= esc($user['created_at']) ?></td>
                                <td>
                                    <form action="<?= base_url('/admin/users/changeRole') ?>" method="post">
                                        <input type="hidden" name="ID" value="<?= esc($user['id']) ?>">
                                        <input type="hidden" name="currentRole" value="<?= esc($user['role']) ?>">
                                        <button type="submit">
                                            <?= $user['role'] === 'admin' ? 'Démotionner' : 'Promouvoir' ?>
                                        </button>
                                    </form>
                                    <form action="<?= base_url('/admin/users/delete') ?>" method="post" style="display:inline;">
                                        <input type="hidden" name="ID" value="<?= esc($user['id']) ?>">
                                        <button type="submit">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </details>
    <?php endforeach; ?>

</main>

</body>
</html>

