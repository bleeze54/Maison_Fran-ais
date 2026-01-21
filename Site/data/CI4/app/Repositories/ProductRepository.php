<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\events\Exclusivite;
use App\Models\events\Reduction;

class ProductRepository extends BaseRepository
{
    protected $exclusiviteModel;
    protected $reductionModel;

    public function __construct()
    {
        parent::__construct(new Product());
        $this->exclusiviteModel = new Exclusivite();
        $this->reductionModel = new Reduction();
    }

    public function getByCategory(string $category): array
    {
        $rows = $this->model->where('category', $category)->findAll();
        return array_map(fn($r) => (object) $r, $rows);
    }

    //deporte la logique de tri des categories avec exclusivite en premier dans le repository
    public function getByCategoryWithExclusivityFirst(
    string $category,
    bool $includeReductions = true
): array {
    $produits = $this->model->where('category', $category)->findAll();

    $excluParProduit = [];
    $nonExclu = [];

    foreach ($produits as $produit) {

        if ($includeReductions) {
            $produit['reduction'] = $this->reductionModel->getbyIDP($produit['id']);
        }

        $isExclu = $this->exclusiviteModel->getbyIDP($produit['id']);

        if ($isExclu == 1) {
            $excluParProduit[] = $produit;
        } else {
            $nonExclu[] = $produit;
        }
    }

    $ordered = array_merge($excluParProduit, array_values($nonExclu));
    $reductionsParProduit=[];
    foreach ($ordered as $index => $produit) {
        $redu = $this->reductionModel->getbyIDP($produit['id']);
        $reductionsParProduit[$index] = $redu;
    }


    return [
        'reducs' => $reductionsParProduit,
        'products'   => $ordered,
        'excluCount' => count($excluParProduit),
    ];
}
}