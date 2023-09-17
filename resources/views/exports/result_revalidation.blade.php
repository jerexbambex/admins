@if ($course)
    <table class="table">
        <tbody>
            <tr>
                <th>Course Name</th>
                <td>{{ "$course->thecourse_title ($course->thecourse_code)" }}</td>
            </tr>
            <tr>
                <th>Course Unit / Status</th>
                <td>{{ "$course->thecourse_unit / $course->thecourse_cat" }}</td>
            </tr>
        </tbody>
    </table>
@endif

<table class="table table-hover table-bordered">
    <thead>
        <th>S/N</th>
        <th>Student Name</th>
        <th>Matric Number</th>
        <th>C.A</th>
        <th>Mid. Semester</th>
        <th>Examination</th>
        <th>Total</th>
    </thead>
    <tbody>
        @foreach ($course_regs as $cr)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $cr->student->full_name }}</td>
                <td>{{ $cr->student->matric_no }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
