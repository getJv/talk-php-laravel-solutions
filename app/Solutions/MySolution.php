<?php

namespace App\Solutions;

use Spatie\ErrorSolutions\Contracts\Solution;

class MySolution implements Solution
{
    public function getSolutionTitle(): string
    {
        return 'My regular custom solution';
    }

    public function getSolutionDescription(): string
    {
        return 'Description from a regular custom solution';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Spatie docs' => 'https://spatie.be/docs',
        ];
    }

}
