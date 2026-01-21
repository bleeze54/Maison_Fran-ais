<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductSizeModel;
use App\Models\events\Reduction;
use App\Models\FavorisModel;

class ProductController extends BaseController
{
    public function show($id)
    {
        $Product = new Product();
        $reductionModel = new Reduction();
        $produit = $Product->find($id);



        if (!$produit) {
            return redirect()->back()->with("error","");
        }

        $images = json_decode($produit['images'], true) ?? [];

        $reduction = $reductionModel->getbyIDP($id);

        $prix = (float) $produit['prix'];
        $prixReduit = null;

        if ($reduction !== null) {
            $prixReduit = $prix - ($prix * $reduction / 100);
        }

        $productModel = new Product();
         $produit = $productModel->find($id);


        $productSizeModel = new ProductSizeModel();
        $sizes = $productSizeModel->where('product_id', $id)
                                  ->where('quantite >', 0)
                                  ->findAll();

        $userId = $_COOKIE['userId'] ?? null;
        if ($userId) {
        $favorisModel = new FavorisModel();
        $isFavori = $favorisModel->isFavori($userId, $id);
    }else {
        $isFavori = false;
    }

    // 3. Envoyer la variable à la vue
        return view ('templates/header')
               .view('produit', [
                    'produit' => $produit,
                    'isFavori' => $isFavori,
                    'images' => $images,
                    'sizes' => $sizes,
                    'reduction'   => $reduction,
                    'prixReduit'  => $prixReduit
                ])
                . view('templates/footer');
    }
}
