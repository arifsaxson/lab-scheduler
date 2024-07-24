<?php

namespace App\Filament\Resources\JadwalSayaResource\Pages;

use App\Filament\Resources\JadwalSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalSaya extends EditRecord
{
    protected static string $resource = JadwalSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
