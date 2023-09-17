<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeTypeChangeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'old_progtype',
        'new_progtype',
        'user_id',
        'form_number'
    ];

    function changeable()
    {
        return $this->morphTo();
    }
}
