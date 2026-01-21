<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\events\Reduction;
use App\Models\Product; // added to fetch product details

class AdminReductions extends BaseController
{
    public function index()
{
    // Récupérer tous les utilisateurs
    $reductionModel = new Reduction();
    $reductions = $reductionModel->findAll();

    
    $productModel = new Product();
    foreach ($reductions as &$reduction) {
        $product = $productModel->find($reduction['produit_id']);
        $reduction['produit_nom'] = $product['nom'] ?? 'Inconnu';
    }

    return view('templates/header')
        . view('admin/reductions', [
            'reductions' => $reductions
        ]);
}


    
    public function delete()
    {
        $reductionModel = new Reduction();
        $reductionModel->delete($this->request->getPost('ID'));
        return redirect()->to('/admin/reductions')->with('message', 'réduction supprimée avec succès !');
        
    }

    //page de creation
    public function create()
    {
        return  view('templates/header')
            .view('/admin/addEvent', [
            'type' => 'reduction',
            'userID' => $this->request->getPost('ID'),
        ]);
    }            
    
    //handler de creation
    public function store(){
    
        $eventModel = new Reduction();
        // Insertion de l'événement
        $eventId = $eventModel->insert([
            'produit_id'  => $this->request->getPost('idproduct'),
            'date_debut'  => $this->request->getPost('date_debut'),
            'date_fin'    => $this->request->getPost('date_fin'),
            'pourcentage_reduction'    => $this->request->getPost('pourcentage_reduction'),
        ]);

        if (!$eventId) {
            return redirect()->back()->with('error', 'Erreur lors de l’ajout de l’événement');
        }
        return redirect()->to('/admin/reductions')->with('message', 'exclusivité supprimée avec succès !');
    }

    public function edit()
    {
        $id = $this->request->getPost('ID') ?? $this->request->getVar('ID');
        $reductionModel = new Reduction();
        $reduction = $reductionModel->find($id);

        if (!$reduction) {
            return redirect()->to('/admin/reductions')->with('error', 'Réduction introuvable');
        }

        $productModel = new Product();
        $product = $productModel->find($reduction['produit_id']);
        $reduction['produit_nom'] = $product['nom'] ?? 'Inconnu';

        return view('templates/header')
            . view('admin/editEvent', [
                'type' => 'reduction',
                'event' => $reduction
            ]);
    }

    public function update()
    {
        $id = $this->request->getPost('ID');
        $reductionModel = new Reduction();

        $data = [
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin'   => $this->request->getPost('date_fin'),
            'pourcentage_reduction' => $this->request->getPost('pourcentage_reduction')
        ];

        $updated = $reductionModel->update($id, $data);

        if ($updated === false) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la réduction');
        }

        return redirect()->to('/admin/reductions')->with('message', 'Réduction modifiée avec succès !');
    }

}