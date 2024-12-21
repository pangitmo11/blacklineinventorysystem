<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksLevel extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'stocks_level';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'description',
        'stocks_level_status',
    ];

    // Set default attributes
    protected $attributes = [
        'stocks_level_status' => 4, // Default to active
    ];

    // Define the relationship with the Stocks model
    public function stocks()
    {
        return $this->hasMany(Stocks::class, 'description_id');
    }

    // If you want to customize the date format for the timestamps, you can uncomment the following:
    // protected $dateFormat = 'Y-m-d H:i:s';
}
