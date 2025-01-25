<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamTech extends Model
{
    use HasFactory;

    protected $table = 'team_tech';

    protected $fillable = [
        'tech_name',
    ];

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'tech_name_id');  // Adjust the foreign key name accordingly
    }

}
