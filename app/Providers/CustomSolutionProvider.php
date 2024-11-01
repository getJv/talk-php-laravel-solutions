<?php

namespace App\Providers;


use App\Solutions\MySolution;
use App\Solutions\MySyntaxSolution;
use Spatie\ErrorSolutions\Contracts\HasSolutionsForThrowable;
use Spatie\ErrorSolutions\Contracts\Solution;
use Throwable;

class CustomSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        // return true if you can provide a solution for this exception
        return $throwable->getMessage() === "Division by zero";
    }

    /**
     * @param \Throwable $throwable
     *
     * @return array<int, Solution>
     */
    public function getSolutions(Throwable $throwable): array
    {

        // return an array of solutions
        return [
            new MySyntaxSolution(),
            new MySolution() // You can offer multiple solutions...
        ];
    }
}
