<?php

use App\Enum\Category;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'index::index');
$routes->set404Override('App\Controllers\index::error404');

#connection, inscription, deconnection
$routes->get('/connexion', 'Connexion::index');
$routes->post('/connexion/authenticate', 'Connexion::authenticate');
$routes->get('/deconnexion', 'Connexion::logout');
$routes->get('/inscription', 'Inscription::index');
$routes->post('/inscription/register', 'Inscription::register');
$routes->get('/connexion/logout', 'Connexion::logout');

#Lien annexe footer
$routes->get('/support', 'Support::index');

//contact
$routes->get('/contact', 'ContactController::index');
$routes->post('/contact/submit', 'ContactController::submit');

$routes->get('/mentionLegal', 'MentionLegal::index');

#products

$routes->get('/pantalons', 'Productlist::Pantalons');
$routes->get('/pulls', 'Productlist::Pulls');
$routes->get('/tshirts', 'Productlist::Tshirts'); 
$routes->get('/accessoires', 'Productlist::Accessoires');
$routes->get('produit/(:num)', 'ProductController::show/$1');

$routes->get('favori', 'FavoriController::index');
$routes->get('favori/toggleFavori/(:num)', 'FavoriController::toggleFavori/$1');

#panier
$routes->post('panier/add/(:num)', 'CartController::add/$1');
$routes->get('panier', 'CartController::index');
$routes->get('panier/remove/(:num)/(:any)', 'CartController::remove/$1/$2');
$routes->get('panier/increment/(:num)/(:any)', 'CartController::increment/$1/$2');
$routes->get('panier/decrement/(:num)/(:any)', 'CartController::decrement/$1/$2');
 $routes->get('panier/processcheckout', 'CartController::processCheckout');

#Commande
$routes->get('commande', 'OrderController::index');
$routes->get('commande/infos', 'OrderController::infos');
$routes->post('commande/confirm', 'OrderController::confirm');
$routes->post('commande/annuler/(:num)', 'OrderController::cancel/$1');

//Admin Routes with Admin Filter
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('', 'Admin\AdminHome::index');

    $routes->get("products", "Admin\AdminProduct::index");
    $routes->post("products/delete", "Admin\AdminProduct::delete");
    $routes->get('products/create', 'Admin\AdminProduct::create');
    $routes->post('products/store', 'Admin\AdminProduct::store');
    $routes->post('products/edit', 'Admin\AdminProduct::edit');
    $routes->post('products/update', 'Admin\AdminProduct::update');

    $routes->get("users", "Admin\AdminUser::index");
    $routes->post("users/delete", "Admin\AdminUser::delete");
    $routes->post("users/changeRole", "Admin\AdminUser::changerole");

    $routes->get("reductions", "Admin\AdminReductions::index");
    $routes->post("reductions/delete", "Admin\AdminReductions::delete");
    $routes->get('reductions/create', 'Admin\AdminReductions::create');
    $routes->post('reductions/create', 'Admin\AdminReductions::create');
    $routes->post('reductions/store', 'Admin\AdminReductions::store');
    $routes->post('reductions/edit', 'Admin\AdminReductions::edit');
    $routes->post('reductions/update', 'Admin\AdminReductions::update');

    $routes->get("exclusivites", "Admin\AdminExclusivites::index");
    $routes->post("exclusivites/delete", "Admin\AdminExclusivites::delete");
    $routes->get('exclusivites/create', 'Admin\AdminExclusivites::create');
    $routes->post('exclusivites/create', 'Admin\AdminExclusivites::create');
    $routes->post('exclusivites/store', 'Admin\AdminExclusivites::store');
    $routes->post('exclusivites/edit', 'Admin\AdminExclusivites::edit');
    $routes->post('exclusivites/update', 'Admin\AdminExclusivites::update');

    $routes->get("usertokens", "Admin\AdminUserTokens::index");
    $routes->post("usertokens/delete", "Admin\AdminUserTokens::delete");

    $routes->get('orders', 'Admin\AdminOrders::index');
    $routes->post('orders/updateStatus', 'Admin\AdminOrders::updateStatus');

    $routes->get('tailles', 'Admin\AdminSizes::index');
    $routes->get('tailles/edit/(:num)', 'Admin\AdminSizes::edit/$1');
    $routes->post('tailles/update', 'Admin\AdminSizes::update');


    $routes->get('tickets', 'Admin\AdminTickets::index');
    $routes->post('tickets/updateStatus', 'Admin\AdminTickets::updateStatus');

});
