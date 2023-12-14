<?php

namespace App\Filament\Resources\MatureResource\Pages;

use App\Filament\Resources\MatureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMatures extends ListRecords
{
    protected static string $resource = MatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
