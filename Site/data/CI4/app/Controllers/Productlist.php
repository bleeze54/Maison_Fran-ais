<?php

namespace App\Controllers;
use App\Models\events\Reduction;
use App\Models\Product;
use App\Models\events\Exclusivite;
use App\Repositories\ProductRepository;

class Productlist extends BaseController{

    public function Pantalons()
    {
        $productRepo = new ProductRepository();
        $result = $productRepo->getByCategoryWithExclusivityFirst('PANTALON', true);

        return view('templates/header')
            . view('pantalons', [
                'produits'    => $result['products'],
                'reductions'  => $result['reducs'],
                'exclu'       => $result['excluCount']
            ])
            . view('templates/footer');
    }

    public function Pulls()
    {   
        $productRepo = new ProductRepository();
        $result = $productRepo->getByCategoryWithExclusivityFirst('PULL', true);

        return view('templates/header')
             . view('pulls',[
                   'produits'    => $result['products'],
                'reductions'  => $result['reducs'],
                'exclu'       => $result['excluCount']
               ])
             . view('templates/footer');
    }

    public function Tshirts()
    {
        $productRepo = new ProductRepository();
        $result = $productRepo->getByCategoryWithExclusivityFirst('TSHIRT', true);

        return view('templates/header')
            . view('tshirts', [
                'produits'    => $result['products'],
                'reductions'  => $result['reducs'],
                'exclu'       => $result['excluCount']
            ])
            . view('templates/footer');
    }

    public function Accessoires()
    {
        $productRepo = new ProductRepository();
        $result = $productRepo->getByCategoryWithExclusivityFirst('ACCESSOIRE', true);

        return view('templates/header')
            . view('accessoires', [
                'produits'    => $result['products'],
                'reductions'  => $result['reducs'],
                'exclu'       => $result['excluCount']
            ])
            . view('templates/footer');
    }


    public function Search()
    {
        $query = $this->request->getGet('q');

        $productModel = new Product();
        $products = $productModel->like('nom', $query)->findAll();

        return view('templates/header')
            . view('search_results', ['produits' => $products, 'query' => $query])
            . view('templates/footer');
    }
}
