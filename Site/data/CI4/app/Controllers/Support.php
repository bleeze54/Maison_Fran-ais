<?php

namespace App\Controllers;
use App\Controllers\BaseController;


class Support extends BaseController
{
    public function index()
    {
        return  view('templates/header')
                .view('support')
                .view('templates/footer');
               
    }

}

?>

