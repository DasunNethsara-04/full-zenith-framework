<?php

namespace ZenithPHP\App;

use ZenithPHP\App\Middleware\TestAuthMiddleware;
use ZenithPHP\Core\Http\InitEnv;

// Load environment variables
InitEnv::load();

use ZenithPHP\Core\Http\Router;

/**
 * This file defines the routes for the application.
 * It uses the Router class to map URLs to their corresponding controller actions.
 *
 * Please do not remove or change anything above this comment.
 */

// YOUR ROUTES GO HERE
Router::get('/', 'WelcomeController', 'index');

Router::get('/protected', 'TestController', 'index', [TestAuthMiddleware::class]);