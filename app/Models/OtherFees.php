<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherFees extends Model
{
    use HasFactory;

    protected $table = 'ofield';

    protected $primaryKey = 'of_id';
}
