<?php

namespace App\Factories;

use App\Models\CartItemModel;
use App\Models\OrderModel;
use App\Enum\Order\Priority;
use App\Enum\Order\Status;

class OrderFactory
{
    // Seuil minimum pour passer en livraison prioritaire gratuitement
    private const PRIORITY_STEP = 100;

    /**
     * Crée une commande à partir d'un panier.
     *
     * @param int $cartId
     * @param int $userId
     * @param Priority $deliveryType
     * @return int ID de la commande créée
     */
    public static function createFromCart(
        int $cartId,
        int $userId,
        Priority $deliveryType = Priority::STANDARD,
        string $address = ''
    ): int {
        $cartItemModel = new CartItemModel();
        $orderModel    = new OrderModel();

        // Récupère les items du panier par la relation cart_id(id_panier) == id_panier
        $items = $cartItemModel->where('cart_id', $cartId)->findAll();

        if (empty($items)) {
            throw new \RuntimeException('Panier vide');
        }

        $total = 0;
        $jsonItems = [];
        // Calcule le total et prépare les items au format JSON pour simplifier le stockage car très peu d'opérations seront faites dessus
        foreach ($items as $item) {
            $lineTotal = $item['price_at_add'] * $item['quantity'];
            $total += $lineTotal;

            $jsonItems[] = [
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'size'       => $item['size'],
                'unit_price' => $item['price_at_add'],
                'total'      => $lineTotal
            ];
        }

        // Déterminer la priorité automatiquement (sauf point relais)
        if ($deliveryType !== Priority::RELAIS && $total >= self::PRIORITY_STEP) {
            $deliveryType = Priority::PRIORITAIRE;
        }else {
        }

        // Autoriser l'insert uniquement pour la factory
        $orderModel->authorizeInsert();
        $orderId = $orderModel->insert([
            'user_id'     => $userId,
            'items_json'  => json_encode($jsonItems),
            'total_price' => $total,
            'priority'    => $deliveryType->value,
            'status'      => Status::EN_ATTENTE->value,
            'address'     => $address
        ]);

        return $orderId;
    }
}
