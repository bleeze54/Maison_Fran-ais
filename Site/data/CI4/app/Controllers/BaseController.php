<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // on crée le chemin pour les cookies
            helper('cookie');
            //et on instantie la session
            $session = service('session');

            
    }

    //on verifie si l'utilisateur est connecté
    public function isLoggedIn(): bool
    {
        $userId = $_COOKIE['userId'] ?? null;
        $token = $_COOKIE['tokendeconnexion'] ?? null;  

        $userModel = new \App\Models\UserModel();
        if (!$userId || !$token || !$userModel->validationtoken($userId, $token)) {
            $cookieNames = ['isLoggedIn', 'tokendeconnexion', 'userId', 'userEmail', 'userNom', 'userPrenom'];
        
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
                return false;
        } else {
            return true;
        }
    }
}
