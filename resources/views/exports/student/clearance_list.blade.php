<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ strtoupper(str_replace('_', ' ', $file_name)) }}</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th class="th-lg">Matric No.</th>
                <th class="th-lg">Form No.</th>
                <th class="th-lg">Full Name</th>
                <th class="th-lg">Department</th>
                <th class="th-lg">Submit Status</th>
                <th class="th-lg">Clearance Status</th>
                <th class="th-lg">Date Cleared</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $student)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $student->matric_no }}</td>
                    <td>{{ $student->matset ? $student->matset : $student->matric_no }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->department_name }}</td>
                    <td>{{ $student->submit_status }}</td>
                    <td>{{ $student->clearance_status }}</td>
                    <td>{{ $student->modified_date_cleared }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-danger">
                        No record found!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
