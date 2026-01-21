<?php
namespace App\Models;
use CodeIgniter\Model;

class UserTokenModel extends Model {
    protected $table = 'user_tokens';
    protected $allowedFields = ['user_id', 'token_hash', 'expires_at','created_at'];
    protected $useTimestamps = true;
}