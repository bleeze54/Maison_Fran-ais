<?php

namespace App\Controllers;

use App\Models\UserModel;

class Inscription extends BaseController
{
    public function index()
    {
        return 
            view('templates/header')
           .view('inscription')
           .view('templates/footer');
        
    }

    public function register()
    {
        $userModel = new UserModel();

        // Récupération des données
        $email   = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $nom      = $this->request->getPost('nom');
        $prenom   = $this->request->getPost('prenom');

        // Validation
        $validation = \Config\Services::validation();

        $rules = [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Sauvegarde
        $userModel->save([
            'email'    => $email,
            'password' => $hashedPassword,
            'nom'      => $nom,
            'prenom'   => $prenom
        ]);

        return redirect()->to('/connexion')->with('success', 'Votre compte a été créé avec succès.');
    }
}
