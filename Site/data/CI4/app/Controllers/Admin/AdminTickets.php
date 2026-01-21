<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\UserModel;

class AdminTickets extends BaseController
{
    public function index()
    {
        $ticketModel = new TicketModel();
        
        // Récupérer tous les tickets (du plus récent au plus ancien)
        $tickets = $ticketModel->orderBy('created_at', 'DESC')->findAll();

        return view('templates/header')
            . view('admin/admintickets', ['tickets' => $tickets]);
    }

    public function updateStatus()
    {
        $ticketId  = $this->request->getPost('ticket_id');
        $newStatus = $this->request->getPost('status');

        $ticketModel = new TicketModel();
        
        if (!$ticketModel->find($ticketId)) {
            return redirect()->back()->with('error', 'Ticket introuvable.');
        }

        $ticketModel->update($ticketId, ['status' => $newStatus]);

        return redirect()->back()->with('success', 'Statut du ticket mis à jour.');
    }
}