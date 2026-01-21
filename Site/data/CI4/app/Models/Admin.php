<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id'];
    protected $returnType = 'array';

   public function isAdmin(int $userId): bool
        {
            return $this->where('user_id', $userId)->countAllResults() > 0;
        } 
    
    public function getAdminUsers(): array
    {
        return $this->select('user_id')->findAll();
    }
    
}