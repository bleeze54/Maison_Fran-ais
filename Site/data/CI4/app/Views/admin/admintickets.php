<link rel="stylesheet" href="<?= base_url('assets/css/adminorders.css') ?>"> <div class="admin_orders_page"> <h1>Gestion des tickets support</h1>

    <?php if(session()->getFlashdata('success')): ?>
        <p class="flash_success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if(empty($tickets)): ?>
        <p>Aucun ticket de support pour le moment.</p>
    <?php endif; ?>

    <?php foreach($tickets as $ticket): ?>
        <div class="order_card"> <div class="order_header">
                <span class="order_id">Ticket #<?= esc($ticket['id']) ?></span>
                <span class="order_date">Reçu le : <?= esc($ticket['created_at']) ?></span>
                <span class="order_priority">ID Client : <?= esc($ticket['user_id'] ?? 'Anonyme') ?></span>
            </div>

            <div class="order_user">
                <p><b>Nom complet :</b> <?= esc($ticket['prenom']) ?> <?= esc($ticket['nom']) ?></p>
                <p><b>Email :</b> <a href="mailto:<?= esc($ticket['email']) ?>"><?= esc($ticket['email']) ?></a></p>
            </div>

            <div class="order_items" style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 10px;">
                <b style="display: block; margin-bottom: 10px;">Message du client :</b>
                <p style="white-space: pre-wrap; line-height: 1.5; color: #333;"><?= esc($ticket['message']) ?></p>
            </div>

            <div class="order_status_form">
                <span class="status_badge status-<?= $ticket['status'] ?>">
                    <?= ucfirst(esc($ticket['status'])) ?>
                </span>
                
                <form action="<?= base_url('/admin/tickets/updateStatus') ?>" method="post">
                    <input type="hidden" name="ticket_id" value="<?= esc($ticket['id']) ?>">
                    <select name="status">
                        <option value="ouvert" <?= $ticket['status'] === 'ouvert' ? 'selected' : '' ?>>Ouvert</option>
                        <option value="en_cours" <?= $ticket['status'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="fermé" <?= $ticket['status'] === 'fermé' ? 'selected' : '' ?>>Fermé</option>
                    </select>
                    <button type="submit" class="btn_update">Mettre à jour</button>
                </form>
            </div>

        </div>
    <?php endforeach; ?>

</div>