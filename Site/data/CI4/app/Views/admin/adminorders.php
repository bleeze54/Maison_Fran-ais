<link rel="stylesheet" href="<?= base_url('assets/css/adminorders.css') ?>">

<div class="admin_orders_page">

    <h1>Gestion des commandes</h1>

    <?php if(session()->getFlashdata('success')): ?>
        <p class="flash_success"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php foreach($orders as $order): ?>
        <div class="order_card">

            <div class="order_header">
                <span class="order_id">Commande #<?= esc($order['id']) ?></span>
                <span class="order_date"><?= esc($order['created_at']) ?></span>
                <span class="order_priority"><?= esc($order['priority']) ?></span>
            </div>

            <div class="order_user">
                <p><b>Nom :</b> <?= esc($order['user_nom']) ?></p>
                <p><b>Prénom :</b> <?= esc($order['user_prenom']) ?></p>
                <p><b>Email :</b> <?= esc($order['user_email']) ?></p>
                <p><b>Adresse :</b> <?= esc($order['address']) ?></p>
            </div>

            <div class="order_items">
                <b>Items commandés :</b>

                <?php if (!empty($order['items_detail'])): ?>
                    <ul class="admin_item_list" style="list-style:none; padding:0; margin:8px 0 0;">
                        <?php foreach ($order['items_detail'] as $it): ?>
                            <li style="display:flex; align-items:center; gap:10px; padding:8px 0; border-top:1px solid #eee;">
                                <img src="<?= base_url($it['image']) ?>" alt="<?= esc($it['nom']) ?>" style="width:44px; height:44px; object-fit:cover; border-radius:6px;">
                                <div style="flex:1;">
                                    <div style="font-weight:600;"><?= esc($it['nom']) ?></div>
                                    <small>
                                        Catégorie: <?= esc($it['categorie'] ?? '-') ?> -
                                        Taille: <?= esc($it['size']) ?>
                                    </small>
                                </div>
                                <div style="min-width:80px; text-align:right; font-weight:600;">
                                    Qté: <?= (int)$it['quantity'] ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <ul>
                        <?php 
                            $items = json_decode($order['items_json'], true);
                            foreach($items as $item):
                                $productName = $item['product_name'] ?? ('Produit #' . $item['product_id']);
                        ?>
                            <li><?= esc($item['quantity']) ?> × <?= esc($productName) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="order_status_form">
                <span class="status_badge status-<?= $order['status'] ?>"><?= esc($order['status']) ?></span>
                <form action="<?= base_url('/admin/orders/updateStatus') ?>" method="post">
                    <input type="hidden" name="order_id" value="<?= esc($order['id']) ?>">
                    <select name="status">
                        <?php foreach($statusEnum as $status): ?>
                            <option value="<?= $status->value ?>" <?= $status->value === $order['status'] ? 'selected' : '' ?>>
                                <?= $status->value ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn_update">Modifier</button>
                </form>
            </div>

        </div>
    <?php endforeach; ?>

</div>
