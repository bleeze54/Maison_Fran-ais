<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\events\Reduction;
use App\Models\ProductSizeModel;
use App\Models\CartModel;
use App\Models\UserModel;
use App\Models\CartItemModel;
use App\Models\Admin;

class CartController extends BaseController
{
    private function adminCheck($userId)
    {
         $adminModel = new Admin();
        if ($adminModel->isAdmin($userId)) {
                return true;
            }
            return false;
    }
    public function index()
    {
        if ($this->adminCheck($_COOKIE['userId'] ?? null)) {
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
        $reductionModel = new Reduction();
        
        $userId = $_COOKIE['userId'] ?? null;

        $cartRaw = [];

        // 1. Déterminer si on utilise le Cookie ou la DB
        if (!$this->isLoggedIn()) {
            // Utilisateur NON connecté : on aplatit le cookie pour la boucle
            $cookieData = $this->getCartFromCookie();
            foreach ($cookieData as $productId => $sizes) {
                foreach ($sizes as $sizeName => $details) {
                    $cartRaw[] = [
                        'product_id' => $productId,
                        'size'       => $sizeName,
                        'quantity'   => $details['quantite']
                    ];
                }
            }
        } else {
            // Utilisateur CONNECTÉ
            $cartModel = new CartModel();
            $cartItemModel = new CartItemModel();
            $userCart = $cartModel->where('user_id', $userId)->first();
            
            if ($userCart) {
                $cartRaw = $cartItemModel->where('cart_id', $userCart['id'])->findAll();
            }
        }

        // 2. Formater les données pour la vue
        $produitModel = new Product();
        $formattedCart = [];
        $grandTotal = 0;

        foreach ($cartRaw as $item) {
            $id = $item['product_id'];
            $product = $produitModel->find($id);

            if (!$product) continue;

            $prixBase = (float)$product['prix'];
            $tauxReduction = $reductionModel->getbyIDP($id);
            
            $prixFinal = $prixBase;
            if ($tauxReduction > 0) {
                $prixFinal = $prixBase * (1 - ($tauxReduction / 100));
            }

            $sousTotal = $prixFinal * $item['quantity'];
            $grandTotal += $sousTotal;

            $images = json_decode($product['images'], true);

            $formattedCart[] = [
                'id'         => $id,
                'image'      => $images[0] ?? 'default.jpg',
                'nom'        => $product['nom'],
                'taille'     => $item['size'],
                'quantite'   => $item['quantity'],
                'prix_base'  => $prixBase,
                'prix_final' => $prixFinal,
                'reduction'  => $tauxReduction,
                'sous_total' => $sousTotal
            ];
        }

        $data = [
            'items' => $formattedCart,
            'total' => $grandTotal
        ];

        return view('templates/header')
            . view('panier', $data)
            . view('templates/footer');
    }


    public function add($id)
    {
        if ($this->adminCheck($_COOKIE['userId'] ?? null)) {
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
        $taille = $this->request->getPost('taille');
        $qteDemandee = (int) $this->request->getPost('quantite');

        if ($qteDemandee < 1)
            return redirect()->back();

        //Modèles
        $productModel = new Product();
        $reductionModel = new Reduction();
        $sizeModel = new ProductSizeModel();
        $cartModel = new CartModel();

        //Vérifications Stock
        $produit = $productModel->find($id);
        $sizeInfo = $sizeModel->where('product_id', $id)->where('taille', $taille)->first();

        if (!$produit || !$sizeInfo) {
            return redirect()->back()->with('error', 'Produit ou taille invalide');
        }
        if ($qteDemandee > (int) $sizeInfo['quantite']) {
            return redirect()->back()->with('error', 'Stock insuffisant');
        }

        //Calcul du prix
        $reduction = $reductionModel->getbyIDP($id);
        $prixFinal = ($reduction !== null) ? $produit['prix'] * (1 - $reduction / 100) : (float) $produit['prix'];

        //Vérifier si utilisateur est connecté
        $userId = $_COOKIE['userId'] ?? null;
        if (!$this->isLoggedIn()) {
            //Si l'utilisateur n'est pas connecté, enregistrer dans le cookie
            $cart = $this->getCartFromCookie();

            if (!isset($cart[$id][$taille])) {
                $cart[$id][$taille] = [
                    'nom' => $produit['nom'],
                    'prix' => $prixFinal,
                    'quantite' => 0
                ];
            }
            $cart[$id][$taille]['quantite'] += $qteDemandee;
            $this->setCookieCart($cart);
        } else {
            //Si l'utilisateur est connecté, enregistrer dans la base de données
            $userCart = $cartModel->where('user_id', $userId)->first();
            if (!$userCart) {
                $cartId = $cartModel->insert(['user_id' => $userId]);
                $userCart = $cartModel->find($cartId);
            }
            $cartModel->id = $userCart['id'];
            $cartModel->addProduct($userCart['id'], $id, $qteDemandee, $prixFinal, $taille);
        }
        return redirect()->to('/panier')->with('success', 'Ajouté au panier');
    }




    public function remove($id, $taille)
    {
        $userId = $_COOKIE['userId'] ?? null;
        if ($this->adminCheck($userId)) {
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
        if (!$this->isLoggedIn()) {
            $cart = $this->getCartFromCookie();
            unset($cart[$id][$taille]);
            $this->setCookieCart($cart);
        } else {
            $cartModel = new CartModel();
            $cartitemModel = new CartItemModel();
            $userCart = $cartModel->where('user_id', $userId)->first();
            if ($userCart) {
                $cartitemModel->where('cart_id', $userCart['id'])
                    ->where('product_id', $id)
                    ->where('size', $taille)
                    ->delete();
            }
        }
        return redirect()->to('/panier');
    }

    public function increment($id, $taille)
    {   
        if ($this->adminCheck($_COOKIE['userId'] ?? null)) {
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
        
        $sizeModel = new ProductSizeModel();
        $size = $sizeModel
            ->where('product_id', $id)
            ->where('taille', $taille)
            ->first();
        $userId = $_COOKIE['userId'] ?? null;
        //si l'utilisateur n'est pas connecté, récupérer le panier depuis la session
        if (!$this->isLoggedIn()) {
            $cart = $this->getCartFromCookie();
            if (isset($cart[$id][$taille]['quantite'])) {
                $currentQuantity = $cart[$id][$taille]['quantite'];
                if ($currentQuantity + 1 < $size['quantite']) {
                    $cart[$id][$taille]['quantite']++;
                }
            }
            $this->setCookieCart($cart);
            //si l'utilisateur est connecté, récupérer le panier depuis la base de données
        } else {
            $cartModel = new CartModel();
            $cartitemModel = new CartItemModel();
            $userCart = $cartModel->where('user_id', $userId)->first();
            if ($userCart) {
                $cartitem = $cartitemModel->where('cart_id', $userCart['id'])
                    ->where('product_id', $id)
                    ->where('size', $taille)->first();
                $currentQuantity = $cartitem['quantity'];
                if ($currentQuantity + 1 < $size['quantite']) {
                    $cartitem['quantity'] = $currentQuantity + 1;
                }
                $cartitemModel->save($cartitem);
            }
        }
        return redirect()->to('/panier');
    }

    public function decrement($id, $taille)
    {
        if ($this->adminCheck($_COOKIE['userId'] ?? null)) {
            return redirect()->to('/')->with('error', 'Accès refusé');
        }
        $userId = $_COOKIE['userId'] ?? null;
        //si l'utilisateur n'est pas connecté, récupérer le panier depuis la session
        if (!$this->isLoggedIn()) {
            $cart = $this->getCartFromCookie();
            if (
                isset($cart[$id][$taille]['quantite']) &&
                $cart[$id][$taille]['quantite'] > 1
            ) {
                $cart[$id][$taille]['quantite']--;
            } else {
                unset($cart[$id][$taille]);
            }
            $this->setCookieCart($cart);
            //si l'utilisateur est connecté, récupérer le panier depuis la base de données
        } else {
            $cartModel = new CartModel();
            $cartitemModel = new CartItemModel();
            $userCart = $cartModel->where('user_id', $userId)->first();
            if ($userCart) {
                $cartitem = $cartitemModel->where('cart_id', $userCart['id'])
                    ->where('product_id', $id)
                    ->where('size', $taille)->first();
                if ($cartitem) {
                    if ($cartitem['quantity'] > 1) {
                        $cartitem['quantity']--;
                        $cartitemModel->save($cartitem);
                    } else {
                        //si la quantité est 1, supprimer l'élément du panier car peut pas avoir 0 ou moins
                        $cartitemModel->delete($cartitem['id']);
                    }

                }

            }
        }
        return redirect()->to('/panier');
    }

    private function getCartFromCookie(): array
    {
        $cookie = $this->request->getCookie('cart'); // Utilisation de l'helper CodeIgniter
        if ($cookie) {
            return json_decode($cookie, true) ?? [];
        }
        return [];
    }

    private function setCookieCart(array $cart): void
    {
        $value = json_encode($cart);
        // Utilisation de la fonction native ou helper CI4
        setcookie('cart', $value, [
            'expires' => time() + (3600 * 24 * 30),
            'path' => '/',
            'httponly' => true, // Sécurité
            'samesite' => 'Lax'
        ]);
    }

    public function processCheckout()
{
    $userModel = new UserModel();

    if (!$this->isLoggedIn()) {
        return redirect()->to('/connexion?next=checkout')->with('info', 'Veuillez vous connecter pour commander');
    }
    return redirect()->to('commande/infos');
}
}

