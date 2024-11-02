<?php

use App\Solutions\ExceptionWithRunnable;
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

Route::get('/custom-solution-provider', function () {
    return 100/0;
});

Route::get('/runnable-solution', function () {
    throw new ExceptionWithRunnable();
});

Route::get('/ai-solution', function () {
    throw new \App\Solutions\ExceptionWithAi("We cant fint the word 'potato'. Review your regex.");
});
