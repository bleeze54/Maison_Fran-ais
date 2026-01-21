<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\Admin;

class AdminUser extends BaseController
{
    public function index()
{
    $userModel = new UserModel();
    $Adminmodel= new Admin();
    // Récupérer tous les utilisateurs

    $users = $userModel->findAll();

    // Récupérer les utilisateurs admins
    $adminUsers = $Adminmodel->getAdminUsers();
    $adminIds = array_column($adminUsers, 'user_id');

    // Séparer les utilisateurs en admins et non-admins en un seul tableau avec un rôle
    $allUsers = [];
    foreach ($users as $user) {
        $user['role'] = in_array($user['id'], $adminIds) ? 'admin' : 'nonAdmin';
        $allUsers[] = $user;
    }

    return view('templates/header')
        . view('admin/users', [
            'allUsers' => $allUsers
        ]);
}


    
    public function delete()
    {
        $userModel = new UserModel();
        $userModel->delete($this->request->getPost('ID'));
        return redirect()->to('/admin/users')->with('message', 'utilisateur supprimé avec succès !');
        
    }

    public function changerole(){
        $Adminmodel= new Admin();
        $userid = intval($this->request->getPost('ID'));
        echo $userid;
        if ($Adminmodel->isAdmin($userid)){
            echo'salut';
            $Adminmodel->where('user_id', $userid)->delete();
            return redirect()->to('/admin/users')->with('message', 'utilisateur retrogradé avec succès !');
        }else{
            print'dazdazd';
            echo'dazdazd';
            $Adminmodel->save(['user_id' => $userid]);
            return redirect()->to('/admin/users')->with('message', 'utilisateur promu avec succès !');
        }
        
        
    }
}