<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UserTokenModel;

class Connexion extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);

        return view('templates/header')
             . view('connexion')
             . view('templates/footer');
    }

    public function authenticate()
    {
        helper(['form', 'url']);

        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Récupération de l'utilisateur
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Identifiants incorrects.');
        }


        // Vérification du mot de passe hashé
        if (!password_verify($password, $user['password'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Identifiants incorrects.');
        }

    $userid = $user['id']; 

    //Générer un token de connexion pour ne pas stocké directement les infos critiques en claire sur la session
    $rawToken = bin2hex(random_bytes(32)); 

    //Hasher le token (pour la base de données)
    $tokenHash = hash('sha256', $rawToken);

    //Enregistrer en base
    $tokenModel = new UserTokenModel();
    $tokenModel->where('user_id', $userid)->delete();
    // on determine la durée de vie du token
    $remember = !empty($this->request->getPost('remember'));
    $duration = $remember ? '+1 years' : '+1 days';

    //Préparation des données
    $data = [
        'user_id'    => $userid,
        'token_hash' => $tokenHash,
        'expires_at' => date('Y-m-d H:i:s', strtotime($duration))
    ];

    $tokenModel->save($data);

    // Gestion du cookie (si nécessaire pour le client)
    if ($remember) {
        $expireTime = time() + (365 * 24 * 60 * 60);
    } else {
        // use session cookie when not 'remember'
        $expireTime = 0;
    }

    // Detect if the request is secure (https)
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

    $cookies = [ 
        'isLoggedIn'       => '1',
        'tokendeconnexion' => $rawToken,
        'userId'           => (string) $userid,
        'userEmail'        => $user['email'],
        'userNom'          => $user['nom'],
        'userPrenom'       => $user['prenom'],
    ];

    foreach ($cookies as $name => $value) {
        setcookie($name, $value, [
            'expires'  => $expireTime,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        // also set into $_COOKIE for immediate availability in this request
        if ($expireTime === 0) {
            $_COOKIE[$name] = $value;
        } else {
            $_COOKIE[$name] = $value;
        }
    }

    $cartData = $_COOKIE['cart'] ?? null;
    
    if ($this->request->getPost('next') == 'checkout') {
        if ($cartData) {
            $this->transferCookieCartToDatabase($userid);
        }
        return redirect()->to('commande/infos'); 
    }else{
         return redirect()->to('/'); // Redirection par défaut
    }
}
    
    
    public function logout(){
        // Check cookie-based login
        if (! isset($_COOKIE['isLoggedIn'])) {
            return redirect()->to(base_url('/'));
        }

        $userId = $_COOKIE['userId'] ?? null;

        if ($userId) {
            $userTokenModel = new UserTokenModel();
            $userTokenModel->where('user_id', $userId)->delete();
        }

        // on netoie les cookies
        $cookieNames = ['isLoggedIn','tokendeconnexion','userId','userEmail','userNom','userPrenom'];
        foreach ($cookieNames as $name) {
            setcookie($name, '', [
                'expires'  => time() - 3600,
                'path'     => '/',
                'secure'   => true,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            unset($_COOKIE[$name]);
        }

        return redirect()->to(base_url('/'))->with('success', 'Déconnecté.');
    }

   private function transferCookieCartToDatabase($userId)
{
    $cartData = $_COOKIE['cart'] ?? null;
    if (!$cartData) return;

    $items = json_decode($cartData, true);
    if (empty($items)) return;

    $cartModel = new \App\Models\CartModel();
    $cartItemModel = new \App\Models\CartItemModel();
    $productModel = new \App\Models\Product(); // Nécessaire pour le prix
    $reductionModel = new \App\Models\events\Reduction(); // Nécessaire pour la réduction

    $userCart = $cartModel->where('user_id', $userId)->first();
    if (!$userCart) {
        $cartId = $cartModel->insert(['user_id' => $userId]);
    } else {
        $cartId = $userCart['id'];
        $cartItemModel->where('cart_id', $cartId)->delete();
    }

    foreach ($items as $productId => $sizes) {
        // On récupère les infos du produit une seule fois par ID produit
        $product = $productModel->find($productId);
        if (!$product) continue;

        $prixBase = (float)$product['prix'];
        $tauxReduc = $reductionModel->getbyIDP($productId);
        
        // Calcul du prix final remisé
        $prixFinal = $prixBase;
        if ($tauxReduc > 0) {
            $prixFinal = $prixBase * (1 - ($tauxReduc / 100));
        }

        foreach ($sizes as $sizeName => $details) {
            $cartItemModel->insert([
                'cart_id'      => $cartId,
                'product_id'   => $productId,
                'size'         => $sizeName,
                'quantity'     => $details['quantite'],
                'price_at_add' => $prixFinal
            ]);
        }
    }

    setcookie('cart', '', time() - 3600, '/'); 
    unset($_COOKIE['cart']);
}

}