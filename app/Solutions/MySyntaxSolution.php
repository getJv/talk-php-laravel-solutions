<?php

namespace App\Solutions;

use Spatie\ErrorSolutions\Contracts\Solution;

class MySyntaxSolution implements Solution
{
    public function getSolutionTitle(): string
    {
        return "Hey mister typo guy";
    }

    public function getSolutionDescription(): string
    {
        return "Maybe is time to a coffee! do a break";
    }

    public function getDocumentationLinks(): array
    {
        return [
            'opa' => "http://asdasdas.com"
        ];
    }

}
