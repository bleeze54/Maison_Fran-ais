<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserTokenModel;
class AdminUserTokens extends BaseController{

    public function index(){
    // Récupérer tous les token user
    $userTokenModel = new UserTokenModel();
    $usertokens =  $userTokenModel->findAll();

    return view('templates/header')
        . view('admin/usertokens', [
            'tokens' => $usertokens
        ]);
}


    
    public function delete(){
        $userTokenModel = new UserTokenModel();
        $token = $userTokenModel->find($this->request->getPost('ID'));
        if (isset($token)){
            $userTokenModel->delete();
        return redirect()->to('/admin/usertoken/')->with('message', 'token supprimé avec succès !');
        }else{
            return redirect()->to('/admin/usertoken/')->with('message', 'token introuvable');
        }
        
    }
}
