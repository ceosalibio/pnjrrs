<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'rank';
    protected $fillable = [
        'DivisionCode',
        'Grade',
        'Rank'
    ];
}
