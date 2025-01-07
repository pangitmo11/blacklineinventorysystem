<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksLevel extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'stocks_level';

    // Fillable attributes
    protected $fillable = [
        'description',
        'stocks_level_status',
    ];

    // Relationship to the Stocks table
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'description_id');
    }

    // Relationship to the StockMaterials table
    public function stockMaterials()
    {
        return $this->hasMany(StockMaterial::class, 'description_id');
    }
}
