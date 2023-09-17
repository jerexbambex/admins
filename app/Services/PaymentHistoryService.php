<?php

namespace App\Services;

use App\Models\Transaction;

class PaymentHistoryService
{
    static function getHistoryCount(Int $level_id = 1, Int $prog_type_id = 1, Int $semester = 1, String $type = 'School Fees', Int $session_year = 2020) : int
    {
        $data = Transaction::where('trans_name', 'like', '%'.$type.'%');
        $data->join('stdprofile', 'stdprofile.std_logid', 'stdtransaction.log_id');
        $data->whereTransSemester(SEMESTERS[$semester])->whereLevelid($level_id)->whereProgType($prog_type_id);
        $data->whereTransYear($session_year)->wherePayStatus('paid')->where('log_id', '!=', 0);
        return $data->count();
    }
}