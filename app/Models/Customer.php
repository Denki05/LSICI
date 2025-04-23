<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'officer',
        'is_invitation_generated',
        'attendance',
        'qr_code_path',
    ];

    public const ATTENDANCE = [
        0 => 'Pending',
        1 => 'Attended',
        2 => 'Not Attended',
    ];

    // Optional: akses helper
    public function getAttendanceLabelAttribute()
    {
        return self::ATTENDANCE[$this->attendance] ?? 'Unknown';
    }
}
