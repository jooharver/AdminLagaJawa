<?php

namespace App\Filament\Resources\KomunitasResource\Pages;

use App\Filament\Resources\KomunitasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKomunitas extends EditRecord
{
    protected static string $resource = KomunitasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
