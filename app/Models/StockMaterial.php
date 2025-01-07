<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMaterial extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'stocks_materials';

    // Fillable attributes
    protected $fillable = [
        'description_id',
        'stocks_id',
    ];

    // Relationship to the Stocks table
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stocks_id');
    }

    // Relationship to the StocksLevel table
    public function stocksdescLevel()
    {
        return $this->belongsTo(StocksLevel::class, 'description_id');
    }
}
