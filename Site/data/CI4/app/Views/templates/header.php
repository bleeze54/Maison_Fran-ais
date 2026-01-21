<!doctype html>
<html lang="fr">

<?php
use App\Models\Admin;

$userId = $_COOKIE['userId'] ?? null;
$adminModel = new Admin();
$isAdmin = $userId ? $adminModel->isAdmin($userId) : false;
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Maison Française</title>

  <link rel="stylesheet" href="<?= base_url('assets/css/header.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/global.css') ?>">
</head>

<body class="corps_page">
  
  <?php if (empty($isAdmin) || !$isAdmin): ?>
  <div class="top_bar_promo">
    Livraison prioritaire gratuite à partir de 100€ d'achat
  </div>
  <?php endif; ?>
  <header class="en_tete">
    <div class="conteneur_en_tete">

      <a href="<?= base_url('/') ?>" class="logo_site">
        <img src="<?= base_url('assets/picture/Logo.png') ?>"
            alt="Logo Maison Française"
            class="logo_img">
      </a>

      <nav class="barre_navigation">
        <ul class="nav_liste">

          <li><a href="<?= base_url('/') ?>" class="nav_lien">Accueil</a></li>
          <li><a href="<?= base_url('/') ?>#presentation" class="nav_lien">Présentation</a></li>
          <li><a href="<?= base_url('/') ?>#produits_mise_en_avant" class="nav_lien">Exclusivité</a></li>

          <li class="nav_deroule">
            <a href="<?= base_url('/') ?>#categories" class="nav_lien">Catégories ▾</a>
            <ul class="sous_nav_liste">
              <li><a href="<?= base_url('pantalons') ?>" class="nav_lien">Pantalons</a></li>
              <li><a href="<?= base_url('tshirts') ?>" class="nav_lien">T-shirts</a></li>
              <li><a href="<?= base_url('pulls') ?>" class="nav_lien">Pulls</a></li>
              <li><a href="<?= base_url('accessoires') ?>" class="nav_lien">Accessoires</a></li>
            </ul>
          </li>

          <li><a href="/contact" class="nav_lien">Contact</a></li>

        </ul>
      </nav>




      <div class="actions_header">

        <?php helper('cookie'); ?>

        <?php if (!get_cookie('userNom')): ?>

          <a href="<?= base_url('connexion') ?>" class="bouton_connexion">Se connecter</a>
          <a href="<?= base_url('panier') ?>" class="bouton_panier">Panier</a>

        <?php else: ?>

          <div class="menu_compte">

            <button class="bouton_compte">
              <?= esc(get_cookie('userPrenom')) ?>
              <?= esc(get_cookie('userNom')) ?>
              <?= $isAdmin ? '(Admin)' : '' ?> ▾
            </button>

            <ul class="menu_compte_liste">
              <?php if ($isAdmin): ?>
                <li><a href="<?= base_url('admin') ?>">Gestion</a></li>
              <?php else: ?>
                <li><a href="<?= base_url('panier') ?>">Panier</a></li>
                <li><a href="<?= base_url('commande') ?>">Commandes</a></li>
                <li><a href="<?= base_url('favori') ?>">Mes Favoris</a></li>
              <?php endif; ?>
              <li class="separateur"></li>
              <li><a href="<?= base_url('connexion/logout') ?>">Déconnexion</a></li>
            </ul>

          </div>
        <?php endif; ?>

      </div>
      
  </header>

  <?php if ($isAdmin && service('uri')->getSegment(1) === 'admin'): ?>
    <style>
        details.admin_sidebar{
            position:fixed;
            left:18px;
            top:120px;
            width:220px;
            background:#ffffff;
            border:1px solid rgba(0,0,0,0.06);
            border-radius:8px;
            box-shadow:0 6px 18px rgba(0,0,0,0.06);
            padding:6px;
            z-index:1000;
            font-family:inherit;
            overflow:hidden;
        }
        details.admin_sidebar summary{
            list-style:none;
            cursor:pointer;
            padding:8px 10px;
            font-weight:700;
            border-radius:6px;
            user-select:none;
        }
        details.admin_sidebar[open] summary{background:#f3f4f6}
        details.admin_sidebar ul{list-style:none;margin:6px 0 0;padding:0}
        details.admin_sidebar li{margin:6px 0}
        details.admin_sidebar a{display:block;padding:8px 10px;color:#222;text-decoration:none;border-radius:6px}
        details.admin_sidebar a:hover{background:#f5f5f7}
        details.admin_sidebar summary::-webkit-details-marker{display:none}
        details.admin_sidebar summary::marker{display:none}
        @media (max-width:900px){
            details.admin_sidebar{display:none}
        }
    </style>

    <details class="admin_sidebar" open aria-label="Menu administration">
        <summary>Menu administrateur ▾</summary>
        <ul>
            <li><a href="<?= base_url('/admin') ?>">Accueil admin</a></li>
            <li><a href="<?= base_url('/admin/users') ?>">Utilisateurs</a></li>
            <li><a href="<?= base_url('/admin/orders') ?>">Commandes</a></li>
            <li><a href="<?= base_url('/admin/products') ?>">Produits</a></li>
            <li><a href="<?= base_url('/admin/reductions') ?>">Réductions</a></li>
            <li><a href="<?= base_url('/admin/exclusivites') ?>">Exclusivités</a></li>
            <li><a href="<?= base_url('/admin/tailles') ?>">Stocks produits</a></li>
            <li><a href="<?= base_url('/admin/usertokens') ?>">Tokens</a></li>
            <li><a href="<?= base_url('/admin/tickets') ?>">Tickets</a></li>
        </ul>
    </details>
<?php endif; ?>

