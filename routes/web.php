<?php

use App\Http\Controllers\AsriController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AsriController::class, 'index'])->name('asri');
