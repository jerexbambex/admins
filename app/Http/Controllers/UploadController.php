<?php

namespace App\Http\Controllers;

use App\Imports\AdmittedApplicantsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ApplicantCBTScoreImport;
use App\Imports\ChangeOfCourseImport;
use App\Imports\StudentChangeOfCourseImport;

class UploadController extends Controller
{
    function uploadApplicantsCbtScore(Request $request)
    {
        try {
            $request->validate([
                'file'    =>  'required|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new ApplicantCBTScoreImport(), $request->file('file'));

            session()->flash('success_toast', 'Upload successful');
            return redirect()->back();
        } catch (\Throwable $th) {
            session()->flash('error_toast', 'Unable to upload');
            return redirect()->back();
        }
    }

    function uploadAdmittedApplicants(Request $request)
    {
        try {
            $request->validate([
                'adm_year'    =>  'required',
                'file'    =>  'required|file|mimes:xlsx,xls,csv',
            ]);

            Excel::import(new AdmittedApplicantsImport($request->adm_year), $request->file('file'));
        } catch (\Throwable $th) {
            session()->flash('error_toast', 'Unable to upload');
        }
        return redirect()->back();
    }

    function changeOfCourseBulkUpload(Request $request)
    {
        $request->validate([
            'faculty_id'    =>  'required',
            'department_id'    =>  'required',
            'file'    =>  'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            return Excel::import(new ChangeOfCourseImport($request->faculty_id, $request->department_id), $request->file('file'));
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    function studentChangeOfCourseBulkUpload(Request $request)
    {
        $request->validate([
            'faculty'       =>  'required',
            'department'    =>  'required',
            'programme'     =>  'required',
            'option'        =>  'required',
            'file'          =>  'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            return Excel::import(new StudentChangeOfCourseImport($request->faculty, $request->department, $request->programme, $request->option), $request->file('file'));
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }
}
