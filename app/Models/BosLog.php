<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BosLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id',
        'adm_year',
        'session',
        'level_id',
        'semester_id',
        'prog_id',
        'prog_type_id',
        'bos_number',
        'presentation',
    ];
}
