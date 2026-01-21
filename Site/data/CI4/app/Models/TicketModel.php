<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'support_tickets';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'nom', 'prenom', 'email', 'message', 'status'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}