<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\UserModel;
use App\Models\Product;
use App\Enum\Order\Status;

class AdminOrders extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $userModel  = new UserModel();
        $productModel = new Product();

        // Récupérer toutes les commandes
        $orders = $orderModel->orderBy('created_at', 'DESC')->findAll();

        // Récupérer les infos utilisateur pour chaque commande
        foreach ($orders as &$order) {
            $user = $userModel->find($order['user_id']);
            $order['user_nom']    = $user['nom'] ?? '';
            $order['user_prenom'] = $user['prenom'] ?? '';
            $order['user_email']  = $user['email'] ?? '';

            $items = json_decode($order['items_json'] ?? '[]', true) ?? [];
            $details = [];
            foreach ($items as $it) {
                $prod = !empty($it['product_id']) ? $productModel->find($it['product_id']) : null;
                $imgs = $prod && !empty($prod['images']) ? (json_decode($prod['images'], true) ?? []) : [];
                $details[] = [
                    'product_id' => $it['product_id'] ?? null,
                    'nom'        => $prod['nom'] ?? ('Produit #'.($it['product_id'] ?? '?')),
                    'image'      => $imgs[0] ?? 'assets/product/placeholder.png',
                    'size'       => $it['size'] ?? ($it['taille'] ?? '-'),
                    'quantity'   => (int)($it['quantity'] ?? 1),
                    'categorie'  => $prod['categorie'] ?? ($prod['category'] ?? '-'),
                ];
            }
            $order['items_detail'] = $details;
        }
        unset($order);

        return view('templates/header')
            .view('admin/adminorders', ['orders' => $orders, 'statusEnum' => Status::cases()]);
    }

    public function updateStatus()
    {
        $orderId = $this->request->getPost('order_id');
        $newStatus = $this->request->getPost('status');

        $orderModel = new OrderModel();
        $order = $orderModel->find($orderId);

        if (!$order) {
            return redirect()->back();
        }

    
        if ($newStatus === Status::ANNULEE->value) {
            $orderModel->cancelOrder($order);
        } else {
            $orderModel->update($orderId, ['status' => $newStatus]);
        }

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }
}
