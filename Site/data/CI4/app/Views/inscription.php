<main class="page_connexion">
    <section class="connexion_card card">
        <div class="card_left">
            <h1>Créer un compte</h1>

            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alerte erreur">
                <?php foreach(session()->getFlashdata('errors') as $e): ?>
                    <div><?= esc($e) ?></div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div class="alerte succes">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('inscription/register') ?>" method="post" class="form_connexion">
                <?= csrf_field() ?>

                <label class="champ_label">
                    <span>Nom</span>
                    <input type="text" name="nom" required>
                </label>

                <label class="champ_label">
                    <span>Prénom</span>
                    <input type="text" name="prenom" required>
                </label>

                <label class="champ_label">
                    <span>Email</span>
                    <input type="email" name="email" required>
                </label>

                <label class="champ_label">
                    <span>Mot de passe</span>
                    <input type="password" name="password" required>
                </label>

                <button class="bouton_principal" type="submit">Créer mon compte</button>
            </form>

            <p class="texte_centre">Déjà un compte ?
                <a href="<?= base_url('connexion') ?>">Se connecter</a>
            </p>
        </div>

        <div class="card_right">
            <div class="illustration">
                <h2>Rejoignez Maison Française</h2>
                <p>Créez votre compte pour accéder à votre espace client.</p>
            </div>
        </div>
    </section>
</main>

<link rel="stylesheet" href="<?= base_url('assets/css/connexion.css') ?>">
