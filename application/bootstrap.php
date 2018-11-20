<?php

namespace application;

use core;

require 'config.php';

// Подключаем autoloader
require 'application/Autoloader.php';

require 'functions.php';

require 'core/Model.php';
require 'core/View.php';
require 'core/Controller.php';
require 'core/Route.php';

// Запускаем роутинг запросов пользователя
core\Route::start();