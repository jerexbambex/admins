<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'state';

    protected $primaryKey = 'state_id';

    protected $fillable = [
        'state_id', 'state_name'
    ];
}
