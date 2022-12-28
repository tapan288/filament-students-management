<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StudentResource;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
