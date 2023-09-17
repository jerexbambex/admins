<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeAppProgType extends Model
{
    use HasFactory;
    protected $fillable = ['applicant_id', 'initial_prog_type', 'new_prog_type', 'initial_appno', 'new_appno'];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'std_id');
    }
}
