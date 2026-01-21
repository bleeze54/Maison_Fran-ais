<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\UserModel;
use App\Models\Admin;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = $_COOKIE['userId'] ?? null;
        $token = $_COOKIE['tokendeconnexion'] ?? null;

        $userModel = new UserModel();
        if (!$userId || !$token || !$userModel->validationtoken($userId, $token)) {
            $cookieNames = ['isLoggedIn', 'tokendeconnexion', 'userId', 'userEmail', 'userNom', 'userPrenom'];

            foreach ($cookieNames as $name) {
                setcookie($name, '', [
                    'expires' => time() - 3600,
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
                unset($_COOKIE[$name]);
            }
            return redirect()->to('/')->with('error', 'Accès refusé');
        } else {
            $adminModel = new Admin();
            if (!$adminModel->isAdmin($userId)) {
                return redirect()->to('/')->with('error', 'Accès refusé');
            }
            //sinon tout est ok allors on laisse passer

        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //pas nessesaire
    }
}
