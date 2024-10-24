<?php

namespace ZenithPHP\App;

use ZenithPHP\Core\Http\InitEnv;
InitEnv::load();


use ZenithPHP\Core\Http\Router;
Router::get('/', 'WelcomeController', 'index');