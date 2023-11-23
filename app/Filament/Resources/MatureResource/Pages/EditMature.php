<?php

namespace App\Filament\Resources\MatureResource\Pages;

use App\Filament\Resources\MatureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMature extends EditRecord
{
    protected static string $resource = MatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
