<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'attendance', 'guest'];


    protected $appends = ['attendance_status'];
    /**
     * Get the attendance status attribute.
     *
     * @return string
     */
    public function getAttendanceStatusAttribute()
    {
        return match ($this->attendance) {
            1 => 'Hadir',
            2 => 'Tidak Hadir',
            3 => 'Ragu',
            default => 'Tidak diketahui',
        };
    }
}
