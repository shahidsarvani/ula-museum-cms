<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HardwareSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
       'hardware_id',
       'day', //['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']
       'start_time',
       'end_time',
       'is_active',
    ];
}
