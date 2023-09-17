<?php

namespace App\Services;

use App\Models\BosLog;
use App\Models\DeptOption;

class ExamsAndRecordService
{
    /**
     * Summary of resultData
     * @param \App\Models\BosLog $bosLog
     * @param mixed $student_log_id
     * @return \App\Models\Student[]|bool
     */
    static function resultData(BosLog $bosLog, $student_log_id)
    {
        extract($bosLog->toArray());
        $dept_option = DeptOption::find($option_id);
        if (!$dept_option) return false;

        return ResultService::getStudents(
            $session,
            $adm_year,
            $semester_id,
            $level_id,
            $prog_id,
            $prog_type_id,
            $dept_option->dept_id,
            $option_id,
            $bosLog,
            $student_log_id
        );
    }
}
