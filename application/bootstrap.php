<?php

namespace application;

use core;

require 'config.php';

// Подключаем autoloader
require 'application/Autoloader.php';

require 'functions.php';

require 'core/Model/Base.php';
require 'core/View/Base.php';
require 'core/Controller/Base.php';
require 'core/Route.php';

// Запускаем роутинг запросов пользователя
core\Route::start();