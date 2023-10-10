<?php

use App\HTTP\Controller\BaseController;
use App\System\Route\Route;


Route::post('/currency-converter/public/', [BaseController::class, 'read']);

