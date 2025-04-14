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
}
