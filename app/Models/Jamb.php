<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamb extends Model
{
    use HasFactory;

    protected $table = 'jamb';

    protected $primaryKey = 'o_id';

    public $timestamps = false;
}
