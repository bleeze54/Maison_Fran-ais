<?php

namespace App\Controllers;
use App\Controllers\BaseController;


class MentionLegal extends BaseController
{
    public function index()
    {
        return  view('templates/header')
                .view('mentionLegal')
                .view('templates/footer');
               
    }
}

?>