<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'stocks_tbl';

    // Fillable attributes
    protected $fillable = [
        'product_name',
        'description_id',
        'team_tech',
        'subsname',
        'subsaccount_no',
        'account_no',
        'j_o_no',
        'sar_no',
        'serial_no',
        'serial_new_no',
        'ticket_no',
        'date_active',
        'date_released',
        'date_used',
        'date_repaired',
        'status',
    ];

    // Relationship to the StocksLevel table
    public function stocksLevel()
    {
        return $this->belongsTo(StocksLevel::class, 'description_id');
    }

    // Relationship to the StockMaterials table
    public function stockMaterials()
    {
        return $this->hasMany(StockMaterial::class, 'stocks_id');
    }
}
