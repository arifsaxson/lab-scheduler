<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'user_id', 'start_time', 'end_time', 'status','mapel'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = Auth::id();
            }
        });

        static::saving(function ($model) {
            $existingSchedule = Schedule::where('room_id', $model->room_id)
                ->whereDate('start_time', '=', $model->start_time->format('Y-m-d'))
                ->where(function ($query) use ($model) {
                    $query->where('start_time', '<', $model->end_time)
                        ->where('end_time', '>', $model->start_time);
                });

            if ($model->exists) {
                $existingSchedule->where('id', '!=', $model->id);
            }

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
