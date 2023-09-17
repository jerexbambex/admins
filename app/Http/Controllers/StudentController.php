<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Portal;
use App\Models\SchoolSession;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function revertAdmissions(Portal $portal)
    {
        $applicant = Applicant::where('app_no', $portal->appno)->first();
        $applicant->adm_status = 0;
        if ($applicant->save() and $portal->delete())
            return response()->json(['status' => 'success', 'message' => "Student: $applicant->app_no admission reverted!"]);

        return response()->json(['status' => 'error', 'message' => "Unable to revert student: $applicant->app_no's admission!"]);
    }

    public function getAdmittedStudents(Request $request)
    {
        $user = $request->user();
        $cur_session = SchoolSession::latest()->first()->year;
        $paginateSize = $request->paginateSize ? $request->paginateSize : 25;
        $portals = Portal::where('adm_year', $cur_session);
        if ($user->prog_type_id) $portals->where('progtype', $user->prog_type_id);
        // if ($this->prog_id) $portals = $portals->where('prog', $this->prog_id);
        // if ($this->department_id) $portals = $portals->where('dcos', $this->department_id);

        if ($search = $request->search) {
            $portals->where(function ($query) use ($search) {
                $query->where('appno', 'like', '%' . $search . '%')
                    ->orwhere('fullname', 'like', '%' . $search . '%');
            });
        }

        $portals = $portals->paginate($paginateSize);

        return response()->json(['status' => 'success', 'message' => 'successful', "data" => $portals]);
    }

    public function getStudentsData(Request $request)
    {
        $user = $request->user();
        $paginateSize = $request->paginateSize ? $request->paginateSize : 10;
        $status = $request->status ?? 0;
        $today_array = [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')];
        $adm_year = SchoolSession::latest()->first()->year;

        $studentsQuery = Student::select([
            'std_id', 'std_logid', 'matric_no', 'matset', 'surname', 'firstname', 'othernames',
            'cs_status', 'eclearance', 'stddepartment_id', 'stdcourse', 'stdprogramme_id', 'stdlevel',
            'stdprogrammetype_id', 'state_of_origin', 'local_gov', 'stdfaculty_id', 'std_admyear',
            'date_cleared', 'std_photo'
        ])->with('eclearance_files')->whereStdAdmyear($adm_year)->whereEclearance($status);
        $clearedQuery = Student::select('std_id', 'date_cleared')->whereStdAdmyear($adm_year)->whereEclearance(1);
        $unclearedQuery = Student::select('std_id', 'date_cleared')->whereStdAdmyear($adm_year)->whereEclearance(0);

        if ($user->prog_type_id) {
            $studentsQuery->where('stdprogrammetype_id', $user->prog_type_id);
            $clearedQuery->where('stdprogrammetype_id', $user->prog_type_id);
            $unclearedQuery->where('stdprogrammetype_id', $user->prog_type_id);
        }

        $uncleared = $unclearedQuery->count();
        $total_cleared = $clearedQuery->count();
        $cleared_today = $clearedQuery->whereBetween('date_cleared', $today_array)->count();
        $search = null;
        if ($request->search) $search = explode(' ', str_replace(',', '', $request->search));
        if ($search) {
            if (count($search) == 2) $studentsQuery = $studentsQuery->where(function ($query) use ($search) {
                $query->where('surname', 'like', '%' . $search[0] . '%')
                    ->where('firstname', 'like', '%' . $search[1] . '%');
            });
            elseif (count($search) == 3) $studentsQuery = $studentsQuery->where(function ($query) use ($search) {
                $query->where('surname', 'like', '%' . $search[0] . '%')
                    ->where('firstname', 'like', '%' . $search[1] . '%')
                    ->where('othernames', 'like', '%' . $search[2] . '%');
            });
            else $studentsQuery = $studentsQuery->where(function ($query) use ($search) {
                $query->where('matric_no', 'like', '%' . $search[0] . '%')
                    ->orwhere('matset', 'like', '%' . $search[0] . '%')
                    ->orwhere('surname', 'like', '%' . $search[0] . '%')
                    ->orwhere('firstname', 'like', '%' . $search[0] . '%')
                    ->orwhere('othernames', 'like', '%' . $search[0] . '%');
            });
        }

        $students = $studentsQuery->paginate($paginateSize);

        $data = compact('total_cleared', 'uncleared', 'cleared_today', 'students');
        $status = 'success';
        $message = 'successful';

        return response()->json(compact('status', 'message', 'data'));
    }

    public function clear($std_id)
    {
        $student = Student::find($std_id);
        if (!$student)
            return response()->json(['status' => 'error', 'message' => "Student not found!"]);
        $student->eclearance = 1;
        $student->date_cleared = now();
        if ($student->save())
            return response()->json(['status' => 'success', 'message' => 'Student has been cleared']);

        return response()->json(['status' => 'error', 'message' => "Unable to clear student: $student->matric_no!"]);
    }

    public function unClear($std_id)
    {
        $student = Student::find($std_id);
        if (!$student)
            return response()->json(['status' => 'error', 'message' => "Student not found!"]);
        $student->eclearance = 0;
        if ($student->save())
            return response()->json(['status' => 'success', 'message' => 'Student has been uncleared']);

        return response()->json(['status' => 'error', 'message' => "Unable to unclear student: $student->matric_no!"]);
    }
}
