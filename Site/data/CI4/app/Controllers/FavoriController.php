<?php

namespace App\Controllers;
use App\Models\FavorisModel;

class FavoriController extends BaseController
{
    private $favorisModel;
    public function __construct()
    {
        $this->favorisModel = new FavorisModel();
    }
    public function index()
    {
        helper('cookie');
        $userId = get_cookie('userId');

        if (!$userId) {
            return redirect()->to('/login');
        } else {
            $data['favoris'] = $this->favorisModel->getFavori($userId);
            return   view('templates/header')
                    .view('favoris', data: $data)
                    .view('templates/footer');
        }
    }

    public function toggleFavori($produitId)
    {
        $userId = get_cookie('userId');

        if (!$userId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Connectez-vous !']);
        }
        $existing = $this->favorisModel->where(['user_id' => $userId, 'produit_id' => $produitId])->first();

        if ($existing) {
            $this->favorisModel->delete($existing['id']);
            return $this->response->setJSON(['status' => 'removed']);
        } else {
            $this->favorisModel->save(['user_id' => $userId, 'produit_id' => $produitId]);
            return $this->response->setJSON(['status' => 'added']);
        }
    }
}