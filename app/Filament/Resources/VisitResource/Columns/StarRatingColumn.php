<?php

namespace App\Filament\Resources\VisitResource\Columns;

use Filament\Tables\Columns\Column;

class StarRatingColumn extends Column
{
    protected string $view = 'filament.tables.columns.star-rating-column';

    public function getStars(): string
    {
        $rate = $this->getState();
        return str_repeat('★', $rate) . str_repeat('☆', 5 - $rate);
    }
}
