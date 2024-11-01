<?php

namespace App\Solutions;

use Exception;
use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;

class ExceptionWithSolution extends Exception implements ProvidesSolution
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?? 'An custom exception');
    }

    public function getSolution(): Solution
    {
        return new MySolution();
    }
}
