<x-print-layout :title="'Course Structure'">
    <style>
        @media print {
            .break {
                page-break-after: always;
            }
        }
    </style>
    @php
        // $set = explode('/', $session)[0];
        $set = $session;
        
        $levels = DB::table('stdlevel')
            ->where('programme_id', $prog_id)
            ->get();
        $semesters = SEMESTERS;
        
        $user = auth()->user();
        
        $for_cec = $user->prog_type_id == 2 ? 1 : 0;
        
        $department = \App\Models\Department::find($dept_id);
        if (!$department || !$levels) {
            return redirect()->route('dashboard');
        }
        $faculty = $department->faculty;
        $options = $department
            ->department_options()
            ->whereProgId($prog_id)
            ->get(['programme_option', 'do_id']);
        
    @endphp


    @foreach ($options as $option)
        @foreach ($levels as $level)
            @foreach ($semesters as $semester)
                @php
                    $courses = DB::table('courses')
                        ->select(['courses.*'])
                        ->join('dept_options', 'dept_options.do_id', 'courses.stdcourse')
                        ->where('department_id', $dept_id)
                        ->where('stdcourse', $option->do_id)
                        ->where('prog_id', $prog_id)
                        ->where('for_set', 'like', "%$set%")
                        ->where('courses.levels', $level->level_id)
                        ->where('courses.semester', $semester)
                        ->where('for_cec', $for_cec)
                        ->get();
                    
                    if ($level->level_id != 1 and $level->level_id != 3) {
                        $data_session = \App\Models\SchoolSession::where('year', '>', $set)->first(['session']);
                    } else {
                        $data_session = \App\Models\SchoolSession::where('year', $set)->first(['session']);
                    }
                    if (!$data_session && ($level->level_id != 1 and $level->level_id != 3)) {
                        $set_ref = (int) $set + 1;
                        $session_ref = sprintf('%s/%s', $set_ref, (int) $set_ref + 1);
                    } else {
                        $session_ref = $data_session->session;
                    }
                @endphp
                @if (count($courses))
                    <table class="table table-bordered">
                        <tr>
                            <th>Faculty</th>
                            <td>{{ $faculty->faculties_name }}</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $department->departments_name }}</td>
                        </tr>
                        <tr>
                            <th>Programme</th>
                            <td>{{ PROGRAMMES[$prog_id] }}</td>
                        </tr>
                        <tr>
                            <th>Session</th>
                            <td>{{ $session_ref }}</td>
                        </tr>
                        <tr>
                            <th>Set</th>
                            <td>{{ $set }}</td>
                        </tr>
                    </table> <br />

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center">
                                    {{ "$option->programme_option - $level->level_name - $semester" }}
                                </th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Course Title</th>
                                <th>Course Code</th>
                                <th>Course Unit</th>
                                <th>Course Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $course->thecourse_title }}</td>
                                    <td>{{ $course->thecourse_code }}</td>
                                    <td>{{ $course->thecourse_unit }}</td>
                                    <td>{{ $course->thecourse_cat }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="break"></div>
                @endif
            @endforeach
        @endforeach
    @endforeach
</x-print-layout>
