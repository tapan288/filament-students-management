<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\StudentsExport;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\StudentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentResource\RelationManagers;
use Filament\Tables\Actions\Action;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'Academic Management';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->autofocus()
                    ->unique(),
                TextInput::make('email')
                    ->required()
                    ->unique(),
                TextInput::make('phone_number')
                    ->required()
                    ->tel()
                    ->unique(),
                TextInput::make('address')
                    ->required(),

                Select::make('class_id')
                    ->relationship('class', 'name')
                    ->reactive(),

                Select::make('section_id')
                    ->label('Select Section')
                    ->options(function (callable $get) {
                        $classId = $get('class_id');

                        if ($classId) {
                            return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                        }
                    })

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                // TextColumn::make('address')
                //     ->sortable()
                //     ->searchable()
                //     ->toggleable()
                //     ->wrap(),

                TextColumn::make('class.name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('section.name')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                Filter::make('class-section-filter')
                    ->form([
                        Select::make('class_id')
                            ->label('Filter By Class')
                            ->placeholder('Select a Class')
                            ->options(
                                Classes::pluck('name', 'id')->toArray()
                            )
                            ->afterStateUpdated(
                                fn(callable $set) => $set('section_id', null)
                            ),
                        Select::make('section_id')
                            ->label('Filter By Section')
                            ->placeholder('Select a Section')
                            ->options(
                                function (callable $get) {
                                    $classId = $get('class_id');

                                    if ($classId) {
                                        return Section::where('class_id', $classId)->pluck('name', 'id')->toArray();
                                    }
                                }
                            ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['class_id'],
                                fn(Builder $query, $record): Builder => $query->where('class_id', $record),
                            )
                            ->when(
                                $data['section_id'],
                                fn(Builder $query, $record): Builder => $query->where('section_id', $record),
                            );
                    })

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
                Action::make('Download Pdf')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn(Student $record): string => route('student.pdf.download', ['record' => $record]))
                    ->openUrlInNewTab(),

                Action::make('View Qr Code')
                    ->icon('heroicon-o-qr-code')
                    ->url(fn(Student $record): string => static::getUrl('qr-code', ['record' => $record])),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                BulkAction::make('export')
                    ->label('Export Selected')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn(Collection $records) => (new StudentsExport($records))->download('students.xlsx'))
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
            'qr-code' => Pages\ViewQrCode::route('/{record}/qr-code'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
