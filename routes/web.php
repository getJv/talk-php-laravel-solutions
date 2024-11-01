<?php

use App\Solutions\ExceptionWithSolution;
use App\Solutions\ExceptionWithSolutionNotes;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/custom', function () {
    throw new ExceptionWithSolution();
});

Route::get('/custom-with-notes', function () {
    throw new ExceptionWithSolutionNotes();
});
