<?php

namespace App;


InitEnv::load();


Router::get('/', 'WelcomeController', 'index');