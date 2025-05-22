<?php

namespace App\Filament\Resources\KomunitasResource\Pages;

use App\Filament\Resources\KomunitasResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKomunitas extends CreateRecord
{
    protected static string $resource = KomunitasResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
