<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matcode extends Model
{
    use HasFactory;

    protected $table = 'matcode';
    protected $primaryKey = 'mid';
    public $timestamps = false;

    protected $fillable = [
        'mid', 'deptname', 'do_id', 'prog_id',
        'progtype_id', 'deptcode'
    ];
    
}
