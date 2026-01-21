<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\Product;
use App\Models\ProductSizeModel;
use App\Enum\Order\Status;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $allowedFields = [
        'user_id',
        'address',
        'items_json',
        'total_price',
        'priority',
        'status',
        'address'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['checkFactoryAuthorization'];

    private bool $authorizedInsert = false;

   
    public function authorizeInsert(): self
    {
        $this->authorizedInsert = true;
        return $this;
    }






    public function cancelOrder(array $order): bool
    {
        if ($order['status'] !== Status::EN_ATTENTE->value) {
            return false;
        }

        $productModel = new Product();
        $productSizeModel = new ProductSizeModel();

        $items = json_decode($order['items_json'], true);

        foreach ($items as $item) {
            if (!empty($item['size'])) {
                $size = $productSizeModel
                    ->where('product_id', $item['product_id'])
                    ->where('taille', $item['size'])
                    ->first();

                if ($size) {
                    $productSizeModel->update($size['id'], [
                        'quantite' => $size['quantite'] + $item['quantity']
                    ]);
                }
            } else {
                $product = $productModel->find($item['product_id']);
                if ($product) {
                    $productModel->update($item['product_id'], [
                        'quantite' => $product['quantite'] + $item['quantity']
                    ]);
                }
            }
        }

        $this->update($order['id'], [
            'status' => Status::ANNULEE->value
        ]);

        return true;
    }



    protected function checkFactoryAuthorization(array $data)
    {
        if (!$this->authorizedInsert) {
            throw new \RuntimeException(
                'Insertion interdite : utilisez OrderFactory'
            );
        }

        return $data;
    }
}

