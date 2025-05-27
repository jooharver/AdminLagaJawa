<?php

namespace App\Filament\Resources\BookingResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\BookingResource;
use App\Filament\Resources\BookingResource\Widgets\BookingStats;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BookingStats::class,
        ];
    }
}
