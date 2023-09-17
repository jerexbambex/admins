<x-print-layout :title="'Score Sheet'">
    @php
        $lecturerCourse = \App\Models\LecturerCourse::find($lec_course_id);
        $course = \App\Models\Course::find($course_id);
        $programme_type = $lecturerCourse->programme_type_id;
        $current_session = base64_decode($encoded_session);
        $app_session = explode('/', $current_session)[0];

        $semester = "";
        $sem_id = SEMESTER_KEYS[$course->semester];
        if($sem_id == 1) $semester = "First";
        elseif($sem_id == 2) $semester = "Second";
        elseif($sem_id == 3) $semester = "Third";
        
        $students = \App\Models\Student::select(['stdprofile.*', 'student_sessions.log_id', 'student_sessions.session'])
            ->join('student_sessions', 'student_sessions.log_id', 'stdprofile.std_logid')
            ->join('stdtransaction', 'stdtransaction.log_id', 'stdprofile.std_logid')
            ->join('course_reg', 'course_reg.log_id', 'stdprofile.std_logid')
            ->where('student_sessions.session', $current_session)
            ->where('student_sessions.semester', $sem_id)
            ->where('stdtransaction.trans_year', $app_session)
            ->where('stdtransaction.trans_semester', 'like', "%$semester%")
            ->where('course_reg.csemester', 'like', "%$semester%")
            ->where('stdtransaction.trans_name', 'like', '%school%')
            ->where('course_reg.cyearsession', $app_session)
            ->where('stdprofile.stdcourse', $course->stdcourse);
        
        if ($programme_type) {
            $students->where('stdprofile.stdprogrammetype_id', $programme_type);
        }
        $students = $students->groupby('stdprofile.std_logid', 'student_sessions.log_id', 'student_sessions.session')->get();
    @endphp
    <div class="header">
        <div class="text-center align-content-center align-item-center justify-content-center">
            <div class="h2">THE POLYTECHNIC, IBADAN</div>
            <div class="h4">FACULTY OF {{ $lecturerCourse->lecturer->department->faculty->faculties_name }}</div>
            <div class="h5">DEPARTMENT OF {{ $lecturerCourse->lecturer->department->departments_name }}</div>
        </div>
        <div class="text-justify">
            <div class="row">
                <div class="col-5">
                    <div class="h6">ACADEMIC SESSION: <span
                            style="text-decoration:underline;">{{ $current_session }}</span></div>
                </div>
                <div class="col-3">
                    <div class="h6">LEVEL: <span style="text-decoration:underline;">{{ $course->level_text }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h6">TYPE: <span
                            style="text-decoration:underline;">{{ PROG_TYPES[$programme_type] }}</span></div>
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="h6">COURSE TITLE: <span
                            style="text-decoration:underline;">{{ $course->thecourse_title }}</span></div>
                </div>
                <div class="col-4">
                    <div class="h6">COURSE CODE: <span
                            style="text-decoration:underline;">{{ $course->thecourse_code }}</span></div>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>MATRIC NO.</th>
                <th>NAME</th>
                <th>C.A</th>
                <th>MID SEMESTER</th>
                <th>EXAM</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                @if ($result = $student->result($current_session, $course->thecourse_id))
                    <tr>
                        <th>
                            {{ $loop->iteration }}
                        </th>
                        <td>{{ $student->matric_no }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td>{{ PROG_TYPE_SLUGS[$student->stdprogrammetype_id] }}</td>
                        <td>{{ $result->c_a ?? '' }}</td>
                        <td>{{ $result->mid_semester ?? '' }}</td>
                        <td>{{ $result->examination ?? '' }}</td>
                        <td>{{ $result->total ?? '' }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</x-print-layout>
