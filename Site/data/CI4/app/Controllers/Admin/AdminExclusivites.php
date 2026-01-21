<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\events\Exclusivite;
use App\Models\Product; // added to fetch product details

class AdminExclusivites extends BaseController
{
    public function index()
{
    // Récupérer tous les utilisateurs
    $exclusiviteModel = new Exclusivite();
    $exclusivites = $exclusiviteModel->findAll();

    
    $productModel = new Product();
    foreach ($exclusivites as &$exclusivite) {
        $product = $productModel->find($exclusivite['produit_id']);
        $exclusivite['produit_nom'] = $product['nom'] ?? 'Inconnu';
    }

    return view('templates/header')
        . view('admin/exclusivites', [
            'exclusivites' => $exclusivites
        ]);
}


    
    public function delete()
    {
        $exclusiviteModel = new Exclusivite();
        $exclusiviteModel->delete($this->request->getPost('ID'));
        return redirect()->to('/admin/exclusivites')->with('message', 'exclusivité supprimée avec succès !');
        
    }

    public function create()
    {
        return  view('templates/header')
            .view('/admin/addEvent', [
            'type' => 'exclusivite',
            'userID' => $this->request->getPost('ID'),
        ]);
    }            
    

    public function store(){
    
    $eventModel = new Exclusivite();
    // Insertion de l'événement
    $eventId = $eventModel->insert([
        'produit_id'  => $this->request->getPost('idproduct'),
        'date_debut'  => $this->request->getPost('date_debut'),
        'date_fin'    => $this->request->getPost('date_fin'),
    ]);

    if (!$eventId) {
        return redirect()->back()->with('error', 'Erreur lors de l’ajout de l’événement');
    }
    return redirect()->to('/admin/exclusivites')->with('message', 'exclusivité supprimée avec succès !');
}

public function edit()
{
    $id = $this->request->getPost('ID') ?? $this->request->getVar('ID');
    $exclusiviteModel = new Exclusivite();
    $exclusivite = $exclusiviteModel->find($id);

    if (!$exclusivite) {
        return redirect()->to('/admin/exclusivites')->with('error', 'Exclusivité introuvable');
    }

    $productModel = new Product();
    $product = $productModel->find($exclusivite['produit_id']);
    $exclusivite['produit_nom'] = $product['nom'] ?? 'Inconnu';

    return view('templates/header')
        . view('admin/editEvent', [
            'type' => 'exclusivite',
            'event' => $exclusivite
        ]);
}

public function update()
{
    $id = $this->request->getPost('ID');
    $exclusiviteModel = new Exclusivite();

    $data = [
        'date_debut' => $this->request->getPost('date_debut'),
        'date_fin'   => $this->request->getPost('date_fin')
    ];

    $updated = $exclusiviteModel->update($id, $data);

    if ($updated === false) {
        return redirect()->back()->with('error', 'Erreur lors de la mise à jour de l\'exclusivité');
    }

    return redirect()->to('/admin/exclusivites')->with('message', 'Exclusivité modifiée avec succès !');
}

}