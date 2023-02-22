<x-filament::page>
    {!! QrCode::size(200)->generate($record->name) !!}
</x-filament::page>
