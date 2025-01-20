<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortUtilization extends Model
{
    use HasFactory;

    protected $table = 'port_utilization';

    protected $fillable = [
        'municipality',
        'brgy_code',
        'barangay',
        'napcode',
        'longitude',
        'latitude',
        'no_of_deployed',
        'no_of_active',
        'no_of_available',
    ];

}
