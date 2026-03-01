<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToiletReservation extends Model
{
    protected $fillable = ['nickname', 'toilet', 'slot_at'];

    protected function casts(): array
    {
        return [
            'slot_at' => 'datetime',
        ];
    }

    public const TOILETS = ['A', 'B', 'C'];

    public static function slotMinutes(): int
    {
        return 15;
    }
}
