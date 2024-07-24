<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalSayaResource\Pages;
use App\Models\Schedule;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Filament\Forms;
use Filament\Resources\Form;

class JadwalSayaResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $label = 'Jadwal Saya';
    

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Pengajar')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('room.name')->label('Ruangan')->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('l, d F Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_time')
                    ->label('Waktu Selesai')
                    ->dateTime('l, d F Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);

    }
   
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'name')->label('Ruang')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')->label('Pengajar')
                    ->default(auth()->id())
                    ->disabled()
                    ->required(),

                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->required(),

                Forms\Components\DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->required(),

                Forms\Components\TextInput::make('status')
                    ->default('booked')->label('Status Ruangan')
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalSayas::route('/'),
            'create' => Pages\CreateJadwalSaya::route('/create'),
            'edit' => Pages\EditJadwalSaya::route('/{record}/edit'),
        ];
    }
}
