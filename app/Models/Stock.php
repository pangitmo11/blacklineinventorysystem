<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    // The name of the table associated with the model
    protected $table = 'stocks_tbl';

    // The attributes that are mass assignable
    protected $fillable = [
        'product_name',
        'description_id',
        'team_tech',
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

    // The attributes that should be cast to native types
    protected $casts = [
        'date_active' => 'date',
        'date_released' => 'date',
        'date_used' => 'date',
        'date_repaired' => 'date',
        'status' => 'integer',
    ];


    public function descriptionname(){
        return $this->belongsTo(StocksLevel::class, 'description_id');
    }
}
