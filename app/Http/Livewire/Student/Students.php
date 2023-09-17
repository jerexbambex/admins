<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Student;
use Livewire\Component;
use Livewire\WithPagination;

class Students extends Component
{
    public $pagesize = 10;
    
    use WithPagination;
    
    public function render()
    {
        // dd(Student::first()->department);   
        $students = Student::paginate($this->pagesize);
        return view('livewire.student.students', ['students'=> $students]);
    }
}

