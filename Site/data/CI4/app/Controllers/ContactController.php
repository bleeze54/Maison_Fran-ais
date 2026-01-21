<?php

namespace App\Controllers;

use App\Models\TicketModel;

class ContactController extends BaseController
{
    public function index()
    {
        $this->isLoggedIn();

        $data = [
            'userNom'    => $_COOKIE['userNom'] ?? '',
            'userPrenom' => $_COOKIE['userPrenom'] ?? '',
            'userEmail'  => $_COOKIE['userEmail'] ?? '',
        ];

        return  view('templates/header')
                .view('contact', $data)
                .view('templates/footer');
    }

    public function submit()
    {
        $this->isLoggedIn();
        $userId = $_COOKIE['userId'] ?? null;

        $ticketModel = new TicketModel();

        $saveData = [
            'user_id' => $userId,
            'nom'     => $this->request->getPost('nom'),
            'prenom'  => $this->request->getPost('prenom'),
            'email'   => $this->request->getPost('email'),
            'message' => $this->request->getPost('message'),
            'status'  => 'ouvert'
        ];

        if ($ticketModel->insert($saveData)) {
            return redirect()->back()->with('success', 'Votre ticket a bien été enregistré !');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue.');
    }
}