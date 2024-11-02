<?php

namespace App\Solutions;

use Exception;
use Spatie\ErrorSolutions\Contracts\Solution;
use Spatie\ErrorSolutions\Contracts\ProvidesSolution;

class ExceptionWithAi extends Exception implements ProvidesSolution
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?? 'Cant find a Match!');
    }

    public function getSolution(): Solution
    {
        return new MySolutionWithAi($this,config('error-solutions.open_ai_key'));
    }
}
