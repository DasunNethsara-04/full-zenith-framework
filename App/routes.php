<?php

namespace ZenithPHP\App;

use ZenithPHP\Core\Http\InitEnv;
InitEnv::load();

use ZenithPHP\Core\Http\Router;

// PLEASE DO NOT REMOVE OR CHANGE ANYTHING ABOVE

// YOUR ROUTES GOES HERE
Router::get('/', 'WelcomeController', 'index');
