<?php

namespace ZenithPHP\App;

use ZenithPHP\App\InitEnv;
use ZenithPHP\App\Router; // Correct namespace for Router

InitEnv::load();


Router::get('/', 'WelcomeController', 'index');