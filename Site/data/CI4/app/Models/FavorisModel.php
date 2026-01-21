<?php

namespace App\Models;

use CodeIgniter\Model;

class FavorisModel extends Model
{
    protected $table      = 'favoris';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'produit_id'];

    // Vérifie si un produit est en favori pour un utilisateur
    public function isFavori($userId, $produitId)
    {
        return $this->where(['user_id' => $userId, 'produit_id' => $produitId])->first() !== null;
    }
    // Ajoute un produit aux favoris d'un utilisateur
    public function addFavori($userId, $produitId)
    {
        if (!$this->isFavori($userId, $produitId)) {
            $this->insert(['user_id' => $userId, 'produit_id' => $produitId]);
        }
    }
    // Supprime un produit des favoris d'un utilisateur
    public function removeFavori($userId, $produitId)
    {
        $this->where(['user_id' => $userId, 'produit_id' => $produitId])->delete();
    }   
    // Récupère tous les favoris d'un utilisateur
    public function getFavori($userId): array
    {
        return $this->select('produits.*, favoris.id as favori_id')
                ->join('produits', 'produits.id = favoris.produit_id')
                ->where('favoris.user_id', $userId)
                ->findAll();
    }
}