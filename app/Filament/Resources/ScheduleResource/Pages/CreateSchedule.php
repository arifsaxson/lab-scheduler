<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\CreateAction;


class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(), // Menggunakan CreateAction yang baru
        ];
    }
}

