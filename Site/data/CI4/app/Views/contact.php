<section id="contact" class="section_contact">
    <div class="conteneur_section">
        <h2 class="titre_section">Contact / Support</h2>

        <?php if(session()->getFlashdata('success')): ?>
            <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>

        <form class="form_contact card_contact" action="<?= base_url('contact/submit') ?>" method="post">

            <div class="form_row_double">
                <label class="label_champ">Nom
                    <input class="champ_texte" type="text" name="nom" 
                           value="<?= esc($userNom) ?>" 
                           <?= !empty($userNom) ? 'readonly style="background-color: #f0f0f0;"' : '' ?> required>
                </label>

                <label class="label_champ">Prénom
                    <input class="champ_texte" type="text" name="prenom" 
                           value="<?= esc($userPrenom) ?>" 
                           <?= !empty($userPrenom) ? 'readonly style="background-color: #f0f0f0;"' : '' ?> required>
                </label>
            </div>

            <label class="label_champ">Adresse e-mail
                <input class="champ_texte" type="email" name="email" 
                       value="<?= esc($userEmail) ?>" 
                       <?= !empty($userEmail) ? 'readonly style="background-color: #f0f0f0;"' : '' ?> required>
            </label>

            <label class="label_champ">Message
                <textarea class="champ_texte" rows="5" name="message" required placeholder="Décrivez votre problème ici..."></textarea>
            </label>

            <button class="bouton_principal bouton_envoyer" type="submit">Envoyer le ticket</button>
        </form>
    </div>
</section>