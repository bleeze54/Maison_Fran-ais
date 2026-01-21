<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductSizeModel extends Model
{
    protected $table = 'product_sizes';
    protected $allowedFields = ['product_id', 'taille', 'quantite'];
}
