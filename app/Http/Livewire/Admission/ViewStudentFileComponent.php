<?php

namespace App\Http\Livewire\Admission;

use App\Models\Eclearance;
use App\Models\Student;
use Livewire\Component;

class ViewStudentFileComponent extends Component
{
    public $stdId, $get_file;

    protected $listeners = ['startDownload'];

    public function mount($std_logid)
    {
        $this->stdId = $std_logid;
        if(!Student::where('std_logid', $std_logid)) return redirect()->route('admission.student.view');
    }

    // public function downloadAll()
    // {
    //     $files = Eclearance::where('std_id', $this->stdId)->get();
    //     foreach ($files as $file) {
    //         $filename = $file->docname . '.png';
    //         $tempImage = tempnam(sys_get_temp_dir(), $filename);
    //         copy(env('STORAGE_URL') . $file->doc, $tempImage);
    //         // dd(env('STORAGE_URL') . $file->doc);

    //         response()->download($tempImage, $filename);
    //     }
    // }

    public function download($file, $filename, $matric_no)
    {

        $filename = $filename . '(' . $matric_no . ')' . '.png';
        $tempImage = tempnam(sys_get_temp_dir(), $filename);
        copy(env('STORAGE_URL') . $file, $tempImage);

        return response()->download($tempImage, $filename);
    }

    public function render()
    {
        $files = Eclearance::where('std_id', $this->stdId)->get();
        // dd($files);
        return view('livewire.admission.view-student-file-component', compact('files'));
    }
}
