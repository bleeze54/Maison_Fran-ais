
    <main class="page_connexion">
    <section class="connexion_card card">
        <div class="card_left">
        <h1>Se connecter</h1>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alerte erreur"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alerte erreur">
            <?php foreach(session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('connexion/authenticate') ?>" method="post" class="form_connexion">
            <?= csrf_field() ?>
            <input type="hidden" name="next" value="<?= htmlspecialchars($_GET['next'] ?? '') ?>">
            <label class="champ_label">
            <span>Email</span>
            <input type="email" name="email" value="<?= old('email') ?>" required>
            </label>

            <label class="champ_label">
            <span>Mot de passe</span>
            <input type="password" name="password" required>
            </label>

            <div class="row_small">
            <label class="remember">
                <input type="checkbox" name="remember"> Se souvenir de moi
            </label>
            <a class="lien_petit" href="<?= base_url('mot-de-passe-oublie') ?>">Mot de passe oublié ?</a>
            </div>

            <button class="bouton_principal" type="submit">Se connecter</button>
        </form>

        <p class="texte_centre">Pas encore de compte ? <a href="<?= base_url('inscription') ?>">Créer un compte</a></p>
        </div>

        <div class="card_right">
        <div class="illustration">
            <h2>Bienvenue chez Maison Française</h2>
            <p>Accédez à votre espace client pour suivre vos commandes et gérer vos informations.</p>
        </div>
        </div>
    </section>
    </main>

    <link rel="stylesheet" href="<?= base_url('assets/css/connexion.css') ?>">
