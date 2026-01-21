<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $table = 'produits';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'prix', 'description', 'images', 'quantite', 'category'];
}

