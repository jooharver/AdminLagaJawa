<?php

namespace App\Filament\Resources\BookingResource\Widgets;

use Filament\Widgets\ChartWidget;

class BookingOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
