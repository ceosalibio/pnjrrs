<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unitrelation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category',
        'department',
        'section',
        'team',
        'code_rel',
        'office',
        'restrict',
        'rights',
        'hpn',
        'org',
        'updated_by'
    ];
}
