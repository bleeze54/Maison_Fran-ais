<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['email', 'password', 'nom', 'prenom'];

    // Active la gestion automatique de created_at si souhaité
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $dateFormat    = 'datetime';
    

public function validationtoken(int $userid, string $token):bool{
    $tokenModel = new \App\Models\UserTokenModel();


    // On verifie le hash et il faut aussi que l'id correspond
    $tokenHash = hash('sha256', $token);
    $found = $tokenModel->where('token_hash', $tokenHash)
                        ->where('user_id', $userid)
                        ->first();

    if (!$found) {
        return false;
    }

    // On verifie le l'expiration (comparaison de dates)
    $now = Time::now();
    $expiry = Time::parse($found['expires_at']);

    if ($now->isAfter($expiry)) {
        // Le token a expiré : on le supprime
        $tokenModel->delete($found);
        return false;
    }

    return true;
}
}


