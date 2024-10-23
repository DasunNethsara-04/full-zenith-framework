<?php

namespace Controller;

use Controller\Controller;

class WelcomeController extends Controller
{
    public function index()
    {
        $this->view('welcome');
    }
}