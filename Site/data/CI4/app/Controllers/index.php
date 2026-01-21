<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\events\Exclusivite;
use App\Models\events\Reduction;

class index extends BaseController
{
    //pade d'acceuil
    public function index(): string
    {
        $exclusiviteModel = new Exclusivite();
        $exclus =$exclusiviteModel->getAllActive();
        $Product = new Product();
        $produits=[];
        foreach($exclus as $index => $exclu){
            $produits[$index] = $Product->where('id', $exclu['produit_id'] )->first();
        }

        $reductions = [];
        foreach ($produits as $index => $produit) {
            $reductions[$index] = Reduction::getbyIDP((int) $produit['id']);
        }


        return view('templates/header')
             . view('acceuil',['produits' => $produits, 'reductions' => $reductions])
             . view('templates/footer');
    }

    public function error404()
    {
        // On redirige vers la racine avec le message
        return redirect()->to('/')->with('error', 'Page inexistante');
    }
}
