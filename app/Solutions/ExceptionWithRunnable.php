<?php

namespace App\Solutions;

use Exception;
use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;

class ExceptionWithRunnable extends Exception implements ProvidesSolution
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?? 'An solution with runnable exception was found.');
    }

    public function getSolution(): Solution
    {
        return new MyRunnableSolution();
    }
}
