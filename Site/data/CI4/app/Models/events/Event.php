<?php

namespace App\Models\events;

use CodeIgniter\Model;

abstract class Event extends Model
{
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $allowedFields = ['produit_id', 'date_debut', 'date_fin', 'created_at', 'updated_at'];

    /**
     * 1 = actif
     * 2 = pas encore commencé
     * 0 = expiré / invalide
     */
    public function isActive(array $event): int{
        $now = date('Y-m-d');
        $dateDebut = $event['date_debut'] ?? null;
        $dateFin = $event['date_fin'] ?? null;

        if (!$dateDebut || !$dateFin) {
            return 0;
        }

        if ($now < $dateDebut) return 2; // pas encore commencé
        if ($now <= $dateFin) return 1;  // actif
        return 0;                         // expiré
    }


    // méthode pour récupérer tous les events actifs
    public static function getAllActive(): array
    {
        $model = new static(); // instancie le modèle enfant
        $allEvents = $model->findAll();
        $activeEvents = [];

        foreach ($allEvents as $event) {
            $state = $model->isActive($event);
            if ($state === 1) {
                $activeEvents[] = $event;
            } elseif ($state === 0) {
                $model->delete($event); // supprime
            }
        }

        return $activeEvents;
    }

    public static function getbyIDP(int $produit_id): ?int
    {
        $reducModel = new static(); 
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
