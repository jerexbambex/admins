<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantCbtScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_number', 'score'
    ];
}
