<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Product;
use App\Models\ProductSizeModel;

class AdminSizes extends BaseController
{
    public function index()
    {
        $productModel = new Product();
        $productSizeModel = new ProductSizeModel();
        $products = $productModel->findAll();
        foreach ($products as &$product) {
            $sizes = $productSizeModel->where('product_id', $product['id'])->findAll();
            $quantities = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];
            foreach ($sizes as $s) {
                $quantities[$s['taille']] = (int)$s['quantite'];
            }
            $product['sizes'] = $quantities;
        }
        unset($product);
        return view('templates/header') . view('admin/sizes', ['products' => $products]);
    }

    public function edit($id = null)
    {
        $productModel = new Product();
        $product = $productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/tailles');
        }
        $productSizeModel = new ProductSizeModel();
        $sizes = $productSizeModel->where('product_id', $id)->findAll();
        $quantities = ['S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0];
        foreach ($sizes as $s) {
            $quantities[$s['taille']] = (int)$s['quantite'];
        }
        return view('templates/header') . view('admin/edit_sizes', ['product' => $product, 'quantities' => $quantities]);
    }

    public function update()
    {
        $productId = (int)$this->request->getPost('product_id');
        $sizesInput = [
            'S' => (int)$this->request->getPost('S'),
            'M' => (int)$this->request->getPost('M'),
            'L' => (int)$this->request->getPost('L'),
            'XL' => (int)$this->request->getPost('XL'),
            'XXL' => (int)$this->request->getPost('XXL'),
        ];
        $productSizeModel = new ProductSizeModel();
        foreach ($sizesInput as $taille => $qte) {
            $existing = $productSizeModel->where('product_id', $productId)->where('taille', $taille)->first();
            if ($existing) {
                $productSizeModel->update($existing['id'], ['quantite' => $qte]);
            } else {
                $productSizeModel->insert(['product_id' => $productId, 'taille' => $taille, 'quantite' => $qte]);
            }
        }
        return redirect()->to('/admin/tailles')->with('message', 'Stock mis à jour');
    }
}
