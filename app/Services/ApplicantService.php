<?php

namespace App\Services;

use App\Models\Olevel;
use Illuminate\Support\Facades\DB;

class ApplicantService
{

    static function getApplicantOLevelsString($olevels)
    {
        $count = 1;
        $olevels_string = "";
        foreach ($olevels as $olevel)
            $olevels_string .= sprintf("%s(%s),", substr($olevel->subname, 0, 3), $olevel->grade);

        return $olevels_string;
    }

    function getApplicantOLevels($log_id)
    {
        $olevels = Olevel::whereStdId($log_id)->get();
        $olevels_string = "";
        foreach ($olevels as $olevel)
            $olevels_string .= sprintf("%s(%s),", substr($olevel->subname, 0, 3), $olevel->grade);

        return $olevels_string;
    }

    function getJambInfo($log_id)
    {
        return DB::table('jamb')->selectRaw("SUM(jscore) as jambs_sum_jscore, jambno")->whereStdId($log_id)->first();
    }
}
