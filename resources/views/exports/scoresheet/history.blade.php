<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Score Sheet</title>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <th>Session</th>
                <td>{{ $session }}</td>
            </tr>
            <tr>
                <th>Course Code</th>
                <td>{{ $code }}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $dept }}</td>
            </tr>
            <tr>
                <th>Dept. Option</th>
                <td>{{ $opt }}</td>
            </tr>
            <tr>
                <th>Level</th>
                <td>{{ $level }}</td>
            </tr>
            <tr>
                <th>Programme Type</th>
                <td>{{ $prog_type }}</td>
            </tr>
            <tr>
                <th>Set</th>
                <td>{{ $set }}</td>
            </tr>
            <tr>
                <th>Course Title</th>
                <td>{{ $course_title }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th><b>S/N</b></th>
                <th><b>MATRIC NUMBER</b></th>
                <th><b>FULL NAME</b></th>
                <th><b>C.A</b></th>
                <th><b>MID SEMESTER</b></th>
                <th><b>EXAMINATION</b></th>
                <th><b>TOTAL</b></th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $row = 11;
            @endphp
            @forelse ($data as $student)
                @if (!$student->hasResult($session, $course_id))
                    <tr>
                        <th>{{ $count++ }}</th>
                        <td>'{{ $student->matric_no }}</td>
                        <td>{{ $student->full_name }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ "=SUM(D$row:F$row)" }}</td>
                    </tr>
                    @php
                        $row++;
                    @endphp
                @endif
            @empty
                <tr>
                    <td colspan="10" class="text-center text-danger">
                        No record found!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
