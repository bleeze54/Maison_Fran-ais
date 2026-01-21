<?php

namespace App\Models\events;

use CodeIgniter\Model;
use streamWrapper;
use App\Models\events\Event;

class Exclusivite extends Event
{
    protected $table = 'vedettes';
    

    public static function getbyIDP(int $produit_id): ?int
    {
        $reducModel = new Exclusivite(); 
        $activereduc = $reducModel->where('produit_id', $produit_id)->first();

        if (is_null($activereduc)) {
            return null;
        }

        $active = $reducModel->isActive($activereduc);

        if ($active === 1) {
            return 1;
        }

        if ($active === 2) {
            return null; // reduc pas encore commencée
        }

        // reduc expirée ou invalide -> supprimer l'enregistrement (si id présent)
        if (!empty($activereduc['id'])) {
            $reducModel->delete($activereduc['id']);
        }

        return null;
    }
    
}
