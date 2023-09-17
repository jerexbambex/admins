<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppCurrentSession extends Model
{
    use HasFactory;

    protected $table = 'app_current_session';
    public $timestamps = false;
    protected $primaryKey = 'cs_id';
}
