<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Product;
use App\Enum\Category;
class AdminProduct extends BaseController
{
    public function index(): string
    {
        $productModel = new Product();
        $produits = $productModel->findAll();

        $produitsParCategorie = [];

        foreach ($produits as $produit) {
            $categorie = $produit['category'];
            $produitsParCategorie[$categorie][] = $produit;
        }

        return view('templates/header')
               .view('admin/products', [
                   'produitsParCategorie' => $produitsParCategorie
               ]);
              
    }

    public function delete()
    {
        $Product = new Product();
        $Product->delete($this->request->getPost('ID'));
        return redirect()->to('/admin/products')->with('message', 'Produit supprimé avec succès !');
        
    }
    
    public function create()
    {
        // Afficher le formulaire de création de produit

        return  view('templates/header')
                .view('addproduct', [
                'categories' => Category::cases()]);
               
    }
    public function store()
    {
        // Gérer la soumission du formulaire de création de produit

         $userId = session()->get('userId');
        $productModel = new Product();

        $imagesUrls = [];
        $files = $this->request->getFiles();

        if ($files && isset($files['images'])) {
            foreach ($files['images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Crée un nom unique basé sur uniqid + extension
                    $newName = uniqid('prod_', true) . '.' . $file->getExtension();
                    
                    // Déplace dans public/assets/product/
                    $file->move(FCPATH . 'assets/product', $newName);

                    $imagesUrls[] = 'assets/product/' . $newName;
                }
            }
        }

        $productId = $productModel->insert([
            'nom' => $this->request->getPost('nom'),
            'prix' => $this->request->getPost('prix'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'images' => json_encode($imagesUrls),
            'quantite' => array_sum($this->request->getPost('quantite'))
    ]);

        if (!$productId) {
            return redirect()->back()->with('error', 'Erreur lors de l’ajout du produit');
        }


        $quantites = $this->request->getPost('quantite'); // tableau S=>10, M=>5, ...
        $productSizeModel = new \App\Models\ProductSizeModel();

        foreach ($quantites as $taille => $qte) {
            if ($qte > 0) {
                $productSizeModel->insert([
                    'product_id' => $productId,
                    'taille' => $taille,
                    'quantite' => $qte
                ]);
            }
        }

        return redirect()->to('admin/products/create')->with('message', 'Produit ajouté avec succès !');
    }

    public function edit()
    {
        $id = $this->request->getPost('ID') ?? $this->request->getVar('ID');
        $productModel = new Product();
        $product = $productModel->find($id);

        if (!$product) {
            return redirect()->to('/admin/products')->with('error', 'Produit introuvable');
        }

        return view('templates/header')
            . view('admin/editProduct', [
                'product' => $product,
                'categories' => Category::cases()
            ]);
    }

    public function update()
    {
        $id = $this->request->getPost('ID');
        $productModel = new Product();

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'prix' => $this->request->getPost('prix'),
            'category' => $this->request->getPost('category')
        ];

        
        $current = $productModel->find($id);
        $images = json_decode($current['images'] ?? '[]', true) ?: [];

       
        $replaceFiles = $this->request->getFileMultiple('replace_images');
        if (!empty($replaceFiles)) {
            foreach ($replaceFiles as $index => $file) {
             
                if ($file !== null && is_object($file) && method_exists($file, 'isValid') && $file->isValid() && !$file->hasMoved()) {
                    $newName = uniqid('prod_', true) . '.' . $file->getExtension();
                    $file->move(FCPATH . 'assets/product', $newName);
                    $images[$index] = 'assets/product/' . $newName;
                }
            }
        }

    

        $data['images'] = json_encode(array_values($images));

        $updated = $productModel->update($id, $data);

        if ($updated === false) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour du produit');
        }

        return redirect()->to('/admin/products')->with('message', 'Produit modifié avec succès !');
    }

}