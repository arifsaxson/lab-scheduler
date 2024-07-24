<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'user_id', 'start_time', 'end_time', 'status'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            // Memeriksa apakah ada jadwal yang bertabrakan
            $existingSchedule = Schedule::where('room_id', $model->room_id)
                ->whereDate('start_time', '=', $model->start_time->format('Y-m-d'))
                ->where(function ($query) use ($model) {
                    $query->where('start_time', '<', $model->end_time)
                        ->where('end_time', '>', $model->start_time);
                });

            // Jika sedang mengedit, harus mengecualikan jadwal saat ini dari periksaan tumpang tindih
            if ($model->exists) {
                $existingSchedule->where('id', '!=', $model->id);
            }

            // Jika ditemukan jadwal yang bertabrakan, maka validasi gagal
            if ($existingSchedule->exists()) {
                throw ValidationException::withMessages(['start_time' => 'Jadwal tumpang tindih dengan jadwal yang sudah ada.']);
            }
        });
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}