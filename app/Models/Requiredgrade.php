<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requiredgrade extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'requiredgrade';
    protected $fillable = [
        'DivisionCode',
        'GradeCode',
        'GradeName'
    ];
}
