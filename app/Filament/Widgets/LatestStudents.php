<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use App\Models\Student;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestStudents extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Student::query()
            ->latest()
            ->take(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->sortable()
                ->searchable(),
            TextColumn::make('email')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('phone_number')
                ->sortable()
                ->searchable()
                ->toggleable(),
            TextColumn::make('address')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->wrap(),

            TextColumn::make('class.name')
                ->sortable()
                ->searchable(),

            TextColumn::make('section.name')
                ->sortable()
                ->searchable()
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
