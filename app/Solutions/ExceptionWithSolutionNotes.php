<?php

namespace App\Solutions;

use Exception;
use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;

class ExceptionWithSolutionNotes extends Exception implements ProvidesSolution
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?? 'My custom exception with notes');
    }

    public function getSolution(): Solution
    {
        return new MySolutionWithNotes();
    }
}
