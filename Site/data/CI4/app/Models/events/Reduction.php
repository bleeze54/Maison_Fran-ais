<?php

namespace App\Models\events;

use CodeIgniter\Model;
use streamWrapper;
use App\Models\events\Event;

class Reduction extends Event
{
    protected $table = 'reductions';
    protected $allowedFields = ['produit_id', 'date_debut', 'date_fin', 'pourcentage_reduction', 'created_at', 'updated_at'];

    /**
     * Détermine l'état d'une reduc fournie (array).
     * Retourne 1 = active, 2 = pas encore commencée, 0 = expirée/invalid
     */

    public static function getbyIDP(int $produit_id): ?int
    {
        $reducModel = new Reduction();
        $activereduc = $reducModel->where('produit_id', $produit_id)->first();

        if (is_null($activereduc)) {
            return null;
        }

        $active = $reducModel->isActive($activereduc);

        if ($active === 1) {
            return (int) ($activereduc['pourcentage_reduction'] ?? 0);
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
