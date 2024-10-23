<?php

namespace ZenithPHP\App\Controllers;



use ZenithPHP\Core\Controller\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        $this->view('welcome');
    }
}
