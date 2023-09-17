<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $file_name }}</title>
</head>

<body>
    <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th class="th-lg">Matric No.</th>
                <th class="th-lg">Form No.</th>
                <th class="th-lg">Surname</th>
                <th class="th-lg">Firstname</th>
                <th class="th-lg">Othernames</th>
                <th class="th-lg">Department</th>
                <th class="th-lg">Course</th>
                <th class="th-lg">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $student->matric_no }}</td>
                    <td>{{ $student->matset }}</td>
                    <td>{{ $student->surname }}</td>
                    <td>{{ $student->firstname }}</td>
                    <td>{{ $student->othernames }}</td>
                    <td>{{ $student->department_name }}</td>
                    <td>{{ $student->course_name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-danger">
                        No record found!
                    </td>
                </tr>
            @endforelse
    </table>
</body>

</html>
