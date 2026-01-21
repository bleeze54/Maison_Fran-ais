<?php
namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\Product;
use App\Models\ProductSizeModel;
use App\Models\OrderModel;
use App\Factories\OrderFactory;
use App\Enum\Order\Priority;
use \App\Models\events\Reduction ;
use App\Models\Admin;
class OrderController extends BaseController
{
    private function checkAuth()
    {
        $userId = $_COOKIE['userId'] ?? null;
        if ((!$this->isLoggedIn())) {
            return false;
        }
        return $userId;
    }

    private function adminCheck($userId)
    {
         $adminModel = new Admin();
        if ($adminModel->isAdmin($userId)) {
                return true;
            }
            return false;
    }

  public function infos()
{

    $userId = $this->checkAuth();
    if (!$userId) return redirect()->to('/connexion');
    if ($this->adminCheck($userId)) return redirect()->to('/')->with('error', 'Accès refusé : les administrateurs ne peuvent pas passer de commandes.');


    $cartModel = new CartModel();
    $cartItemModel = new CartItemModel();
    $produitModel = new Product();
    $reductionModel = new Reduction(); 



    $cart = $cartModel->where('user_id', $userId)->first();
    if (!$cart) return redirect()->to('/panier');

    $itemsRaw = $cartItemModel->where('cart_id', $cart['id'])->findAll();

    $formattedItems = [];
    $total = 0;

    foreach ($itemsRaw as $item) {
        $product = $produitModel->find($item['product_id']);
        
        if ($product) {
            $images = json_decode($product['images'], true);
            
            $prixBase = (float)$product['prix'];
            $taux = $reductionModel->getbyIDP($item['product_id']);
            $taux = $taux !== null ? (float)$taux : 0.0;

            $derivedReduced = $prixBase * (1 - $taux / 100);

            // Si une réduction existe, on affiche le prix réduit calculé.
            if ($taux > 0) {
                $prixUnit = $derivedReduced;
            } else {
                $prixUnit = isset($item['price_at_add']) ? (float)$item['price_at_add'] : $prixBase;
            }

            $sousTotal = $prixUnit * $item['quantity'];
            $total += $sousTotal;

            $formattedItems[] = [
                'nom'            => $product['nom'],
                'image'          => $images[0] ?? 'default.jpg',
                'taille'         => $item['size'],
                'quantite'       => $item['quantity'],
                'prix_base'      => $prixBase,
                'prix_unit'      => $prixUnit,
                'taux_reduction' => $taux,
                'a_reduction'    => ($prixUnit < $prixBase),
                'sous_total'     => $sousTotal
            ];
        }
    }

    return view('templates/header')
        . view('infocommande', [
            'items' => $formattedItems,
            'total' => $total
        ])
        . view('templates/footer');
}

   
    public function confirm()
    {
        $userId = $this->checkAuth();
        if (!$userId) return redirect()->to('/connexion');
        if ($this->adminCheck($userId)) return redirect()->to('/')->with('error', 'Accès refusé : les administrateurs ne peuvent pas passer de commandes.');

        // Validation simple
        $rules = [
            'address_street' => 'required',
            'address_zip'    => 'required',
            'address_city'   => 'required',
            'card'           => 'required|exact_length[16]|numeric',
            'crypto'         => 'required|exact_length[3]|numeric',
            'priority'       => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cartModel = new CartModel();
        $cartItemModel = new CartItemModel();
        $productModel  = new Product();
        $productSizeModel = new ProductSizeModel();

        $cart = $cartModel->where('user_id', $userId)->first();
        if (!$cart) return redirect()->to('/panier');

        $priority = Priority::from($this->request->getPost('priority'));
        $rue = $this->request->getPost('address_street');
        $cp  = $this->request->getPost('address_zip');
        $ville = $this->request->getPost('address_city');

    //fusion dans une seule variable bien propre
    // Format : "12 rue des Fleurs, 75001 PARIS"
     $address = trim($rue) . ", " . trim($cp) . " " . mb_strtoupper(trim($ville));
        $cartItems = $cartItemModel->where('cart_id', $cart['id'])->findAll();

        foreach ($cartItems as $item) {
        if (!empty($item['size'])) {
            $sizeData = $productSizeModel
                ->where('product_id', $item['product_id'])
                ->where('taille', $item['size'])
                ->first();

            if (!$sizeData || $sizeData['quantite'] < $item['quantity']) {
                return redirect()->back()
                    ->with('errors', ['message' => "Stock insuffisant pour le produit #{$item['product_id']} (taille {$item['size']})"])
                    ->withInput();
            }
        } else {
            $product = $productModel->find($item['product_id']);
            if (!$product || $product['quantite'] < $item['quantity']) {
                return redirect()->back()
                    ->with('errors', ['message' => "Stock insuffisant pour le produit #{$item['product_id']}"])
                    ->withInput();
            }
        }
    }

    
    foreach ($cartItems as $item) {
        if (!empty($item['size'])) {
            $sizeData = $productSizeModel
                ->where('product_id', $item['product_id'])
                ->where('taille', $item['size'])
                ->first();
            $productSizeModel->update($sizeData['id'], [
                'quantite' => $sizeData['quantite'] - $item['quantity']
            ]);
        } else {
            $product = $productModel->find($item['product_id']);
            $productModel->update($item['product_id'], [
                'quantite' => $product['quantite'] - $item['quantity']
            ]);
        }
    }

        $orderId = OrderFactory::createFromCart(
            $cart['id'],
            $userId,
            $priority,
            $address
        );

        // vider le panier
        $cartItemModel->where('cart_id', $cart['id'])->delete();

        return redirect()->to('/commande')->with('success', 'Commande créée');
    }

   
    public function index()
    {
        
        $userId = $this->checkAuth();
        if (!$userId) return redirect()->to('/connexion');
        if ($this->adminCheck($userId)) return redirect()->to('/')->with('error', 'Accès refusé : les administrateurs ne peuvent pas passer de commandes.');

        $orderModel = new OrderModel();
        $orders = $orderModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

    
        $productModel = new Product();
        $reductionModel = new Reduction(); // pour retomber sur le calcul du panier

        foreach ($orders as &$order) {
            $items = json_decode($order['items_json'] ?? '[]', true) ?? [];
            $details = [];

            foreach ($items as $it) {
                $prod = !empty($it['product_id']) ? $productModel->find($it['product_id']) : null;
                $imgs = $prod && !empty($prod['images']) ? (json_decode($prod['images'], true) ?? []) : [];

                $prixBase = (float)($it['prix_base'] ?? $prod['prix'] ?? 0);
                $qty      = (int)($it['quantity'] ?? 1);

          
                $taux = null;
                if (isset($it['taux_reduction'])) {
                    $taux = (float)$it['taux_reduction'];
                } elseif (isset($it['reduction'])) {
                    $taux = (float)$it['reduction'];
                } else {
                    $taux = (float)($reductionModel->getbyIDP($it['product_id'] ?? 0) ?? 0);
                }

                if (isset($it['prix_unit'])) {
                    $prixUnit = (float)$it['prix_unit'];
                } elseif (isset($it['price'])) {
                    $prixUnit = (float)$it['price'];
                } else {
                    $prixUnit = $taux > 0 ? $prixBase * (1 - $taux / 100) : $prixBase;
                }

     
                if ($taux === 0 && $prixBase > 0 && $prixUnit < $prixBase) {
                    $taux = round((1 - ($prixUnit / $prixBase)) * 100);
                }

                $sousTotal = $prixUnit * $qty;

                $details[] = [
                    'product_id'     => $it['product_id'] ?? null,
                    'nom'            => $prod['nom'] ?? ('Produit #'.($it['product_id'] ?? '?')),
                    'image'          => $imgs[0] ?? 'assets/product/placeholder.png',
                    'size'           => $it['size'] ?? ($it['taille'] ?? null),
                    'quantity'       => $qty,
                    'prix_base'      => $prixBase,
                    'prix_unit'      => $prixUnit,
                    'sous_total'     => $sousTotal,
                    'a_reduction'    => ($prixUnit < $prixBase),
                    'taux_reduction' => (float)$taux,
                ];
            }

            $order['items_detail'] = $details;
        }
        unset($order);

        return view('templates/header')
            . view('commande', ['orders' => $orders])
            . view('templates/footer');
    }

  
    public function cancel($id) {
        
        $userId = $_COOKIE['userId'] ?? null;
        if (!$userId) return redirect()->to('/connexion');
        if ($this->adminCheck($userId)) return redirect()->to('/')->with('error', 'Accès refusé : les administrateurs ne peuvent pas passer de commandes.');
        $orderModel = new OrderModel();

        $order = ($orderModel)
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return redirect()->to('/')->with('success', 'Commande créée');
        }

        $orderModel->cancelOrder($order);

        return redirect()->to('/commande')->with('success', 'Commande créée');
    
    }

}