<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Validation\Rule;

use Filament\Tables\Actions\Action as TableAction;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $label = 'Jadwal';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('room_id')
                    ->relationship('room', 'name')->label('Ruang')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')->label('Pengajar')
                    ->default(Auth::user()->id)
                    ->disabled()
                    ->required(),

                Forms\Components\DateTimePicker::make('start_time')
                    ->label('Waktu Mulai')
                    ->withoutSeconds()
                    ->required()
                    ->rules([
                        'after_or_equal:07:00',
                        'before_or_equal:15:00',
                    ]),

                Forms\Components\DateTimePicker::make('end_time')
                    ->label('Waktu Selesai')
                    ->withoutSeconds()
                    ->required()
                    ->rules([
                        'after_or_equal:07:00',
                        'before_or_equal:15:00',
                    ]),


                Forms\Components\TextInput::make('status')
                    ->default('booked')->label('Status Ruangan')
                    ->required(),
                
                Forms\Components\TextInput::make('Mapel')
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('room.name')->label('Ruangan')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('user.name')->label('Pengajar')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('mapel')->label('Mata Pelajaran')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('start_time')->searchable()
                ->label('Waktu Mulai')
                ->dateTime('l, d F Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('end_time')
                ->label('Waktu Selesai')
                ->dateTime('l, d F Y H:i')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->sortable()
                ->formatStateUsing(function ($state, $record) {
                    if ($state === 'booked') {
                        return '<span style="color: red;">' . $state . '</span>';
                    } elseif (now()->greaterThan($record->end_time)) {
                        return '<span style="color: green;">Available</span>';
                    }
                    return $state;
                })
                ->html(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->icon('heroicon-o-eye')->label(''),
                Tables\Actions\EditAction::make()->icon('heroicon-o-pencil')->label(''),
                Tables\Actions\DeleteAction::make()->icon('heroicon-o-trash')->label(''),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    // Tambahkan method ini untuk menambahkan Action sebagai modal
  
   

    // Tambahkan method ini untuk mengisi user_id sebelum membuat jadwal baru
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    // Tambahkan method ini untuk mengisi user_id sebelum menyimpan jadwal yang diedit
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['user_id'] = Auth::id();
        return $data;
    }

    // Pastikan method beforeCreate dan beforeSave dipanggil
    public function beforeCreate(array $data): array
    {
        return $this->mutateFormDataBeforeCreate($data);
    }

    public function beforeSave(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }

}