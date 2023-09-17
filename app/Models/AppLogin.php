<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppLogin extends Model
{
    use HasFactory;

    protected $table = 'applogin';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'log_id', 'log_surname',
        'log_firstname', 'log_othernames',
        'log_username', 'log_password', 'log_gsm',
        'log_email'
    ];
}
