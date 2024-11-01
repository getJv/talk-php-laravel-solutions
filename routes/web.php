<?php

use App\Solutions\ExceptionWithSolution;
use App\Solutions\ExceptionWithSolutionNote;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/custom', function () {
    throw new ExceptionWithSolution();
});

