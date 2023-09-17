<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eclearance extends Model
{
    use HasFactory;

    protected $table = 'e_clearance';

    protected $appends = ['doc_url'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'std_id', 'std_logid');
    }

    public function getDocUrlAttribute()
    {
        return env('STORAGE_URL').$this->doc;
    }
}
