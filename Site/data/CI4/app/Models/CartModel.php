<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table         = 'carts';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id'];
    protected $useTimestamps = true;

    public function addProduct(int $cartId, int $productId, int $quantity, float $priceAtAdd, string $size): bool
    {
        $cartItemModel = model(CartItemModel::class);
        $sizeModel     = model(ProductSizeModel::class);

        $sizeInfo = $sizeModel->where(['product_id' => $productId, 'taille' => $size])->first();
        
        if (!$sizeInfo) {
            return false; 
        }

        $existingItem = $cartItemModel->where([
            'cart_id'    => $cartId,
            'product_id' => $productId,
            'size'       => $size
        ])->first();

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            
            if ($newQuantity > $sizeInfo['quantite']) {
                $newQuantity = $sizeInfo['quantite'];
            }

            return $cartItemModel->update($existingItem['id'], ['quantity' => $newQuantity]);
        } 

        return (bool) $cartItemModel->insert([
            'cart_id'      => $cartId,
            'product_id'   => $productId,
            'size'         => $size,
            'quantity'     => min($quantity, $sizeInfo['quantite']),
            'price_at_add' => $priceAtAdd
        ]);
    }

    public function removeProduct(int $cartId, int $productId): void
    {
        model(CartItemModel::class)
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->delete();
    }
}