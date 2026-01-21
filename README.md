# Structure du projet

## Racine (`eq_3_03_chelli-enzo_fourage-esteban_lucas-lilian_musset-shawn/`)

Le répertoire racine contient l’ensemble des ressources du projet : diagrammes, évaluations, site web et documentation générale.

---

## `/Diagramme`

Contient tous les diagrammes liés à l’analyse et à la conception du projet.

* Diagrammes UML (cas d’utilisation, classes, analyse)
* Diagrammes fonctionnels (connexion, recherche, passage de commande, consultation de produit)
* Fichier source du projet de diagrammes (`Diagramme.vpp`)

---

## `/Evalue`

Contient les différents répertoires correspondant aux matières / ressources évaluées.

Chaque sous-répertoire représente une matière et contient les documents associés à cette ressource.

Exemples :

* `/Evalue/Algo_Cor_Eff` : documents de la ressource Algo_Cor_Eff
* `/Evalue/Analyse` : documents d’analyse
* `/Evalue/Anglais` : travaux d’anglais
* `/Evalue/COM` : documents de communication
* `/Evalue/Design_Pattern` : documents design patterns
* `/Evalue/IAg` : documents IA générative
* `/Evalue/Reseaux` : documents architecture des réseaux
* `/Evalue/Rendu_Final` : documents zip rendus dans les différents dépôts

---

## `/Site`

Contient l’ensemble du site web ainsi que son environnement d’exécution en conteneur.

* `/Site/run.sh` : script de lancement automatique du conteneur hébergeant le site

### Procédure de lancement du site (manuel)

À suivre **si `run.sh` ne fonctionne pas** :

1. Ouvrir un terminal **Silverblue**
2. Se placer dans le répertoire `/Site` du rendu :

   ```bash
   cd /Perso/Documents/2eme_annee/eq_3_03_chelli-enzo_fourage-esteban_lucas-lilian_musset-shawn/Site
   ```
3. Créer le conteneur :

   ```bash
   ./scripts/create.sh
   ```

   *(Renseigner vos identifiants docker.io si demandé)*
4. Pousser l’image :

   ```bash
   ./scripts/push.sh
   ```
5. Accéder au terminal du conteneur :

   ```bash
   ./scripts/terminal.sh
   ```

   Vous arriverez dans `/var/www/html`
6. Se placer dans le répertoire CI4 :

   ```bash
   cd CI4
   ```
7. Installer les dépendances PHP :

   ```bash
   composer install
   ```
8. Quitter le terminal du conteneur :

   ```bash
   exit
   ```
9. Ouvrir le site dans un navigateur :

   ```
   http://localhost:8080
   ```

---

## Mot de passe compte administrateur Site de Vente en ligne de vêtement (Maison Française)

- ### **mail :** `admin@admin.fr`
- ### **mot de passe :** `adminadmin`

## Cahier de recette

### Visiteur / Client

| Fonctionnalité                                                                  | État          |
| ------------------------------------------------------------------------------- | ------------- |
| Navigation sur le site de vente                                                 | Fonctionnelle |
| Consultation des catégories et des produits associés                            | Fonctionnelle |
| Accès à la fiche produit (nom, description, prix, stock, tailles)               | Fonctionnelle |
| Sélection de la taille d’un produit                                             | Fonctionnelle |
| Sélection de la quantité d’un produit                                           | Fonctionnelle |
| Ajout d’un produit au panier (nom, taille, quantité)                            | Fonctionnelle |
| Consultation du panier (liste des produits et prix total du panier)             | Fonctionnelle |
| Modification de la quantité d'un produit dans le panier (+ / −)                 | Fonctionnelle |
| Suppression d’un produit du panier                                              | Fonctionnelle |
| Création d’un compte client                                                     | Fonctionnelle |
| Connexion à un compte client                                                    | Fonctionnelle |
| Déconnexion d’un compte client                                                  | Fonctionnelle |
| Utilisation des sessions utilisateur                                            | Fonctionnelle |
| Ajout d’un produit en favori                                                    | Fonctionnelle |
| Consultation des favoris                                                        | Fonctionnelle |
| Suppression d’un produit depuis les favoris                                     | Fonctionnelle |
| Création d’une commande à partir du panier                                      | Fonctionnelle |
| Consultation de l’historique des commandes avec suivi du statut                 | Fonctionnelle |
| Envoi d’un ticket de support prérempli avec les informations client             | Fonctionnelle |

### Admin

| Fonctionnalité                                                    | État          |
| ----------------------------------------------------------------- | ------------- |
| Connexion à un compte administrateur                              | Fonctionnelle |
| Accès au panneau d’administration                                 | Fonctionnelle |
| Gestion des utilisateurs (promotion, rétrogradation, suppression) | Fonctionnelle |
| Gestion et suivi des commandes clients                            | Fonctionnelle |
| Ajout d’un produit                                                | Fonctionnelle |
| Modification des informations d’un produit                        | Fonctionnelle |
| Suppression d’un produit                                          | Fonctionnelle |
| Création d’une réduction pour un produit                          | Fonctionnelle |
| Création d’une exclusivité pour un produit                        | Fonctionnelle |
| Gestion des stocks (tailles disponibles par produit)              | Fonctionnelle |
| Gestion des réductions (modification / suppression)               | Fonctionnelle |
| Gestion des produits exclusifs (modification / suppression)       | Fonctionnelle |
| Accès au token de connexion administrateur                        | Fonctionnelle |
| Gestion des tickets de support client                             | Fonctionnelle |
| Visualisation des logs(requête web, SQL)                          | Idée |
| Chat d'assistance en temps réel                                   | Idée |



