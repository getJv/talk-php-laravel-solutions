<?php

namespace App\Solutions;

use Spatie\ErrorSolutions\Contracts\Solution;

class MySolutionWithNotes implements Solution
{
    public function getSolutionTitle(): string
    {
        return 'My custom solution with notes';
    }

    public function getSolutionDescription(): string
    {
        return 'My custom solution description';
    }

    public function getDocumentationLinks(): array
    {
        return [
            'Spatie docs' => 'https://spatie.be/docs',
        ];
    }


    public function solutionProvidedByName(): string
    {
        return 'Flare';
    }

    public function solutionProvidedByLink(): string
    {
        return 'https://flareapp.io';
    }
}
