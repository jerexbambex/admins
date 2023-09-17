<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResultsConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_year', 'semester_id', 'student_registration_start_date',
        'student_registration_end_date', 'lecturer_upload_start_date',
        'lecturer_upload_end_date', 'departmental_moderation_start_date',
        'departmental_moderation_end_date', 'bos_moderation_start_date',
        'bos_moderation_end_date', 'for_cec',
        'tuition_payments_start_date', 'tuition_payments_end_date',
        'course_update_fee_start_date', 'course_update_fee_end_date',
        'student_results_enable_start_date', 'student_results_enable_end_date',
    ];

    protected $appends = ['prog_type_string'];

    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_year', 'year');
    }

    public function getProgTypeStringAttribute()
    {
        switch ($this->for_cec) {
            case '0':
                return 'FT & DPP';
                break;

            case '1':
                return 'CEC';
                break;

            case '2':
                return 'ALL';
                break;

            default:
                return '';
                break;
        }
    }
}
