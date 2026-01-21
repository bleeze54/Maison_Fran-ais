<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
class AdminHome extends BaseController
{
    public function index()
    {
        return  view('templates/header')
                .view('admin/home');
               
    }
}