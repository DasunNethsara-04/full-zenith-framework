<?php

namespace ZenithPHP\App;

use ZenithPHP\Core\Http\InitEnv;
use ZenithPHP\Core\Http\Router;

InitEnv::load();
// Correct namespace for Router
Router::get('/', 'WelcomeController', 'index');