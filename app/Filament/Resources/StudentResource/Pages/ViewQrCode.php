<?php

namespace App\Filament\Resources\StudentResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\StudentResource;

class ViewQrCode extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected static string $view = 'filament.resources.student-resource.pages.view-qr-code';

    protected function getActions(): array
    {
        return [];
    }
}
