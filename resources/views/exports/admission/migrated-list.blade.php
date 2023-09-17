<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accepted List</title>
</head>

<body>
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Old Form Number</th>
                <th>New Form Number</th>
                <th>Faculty</th>
                <th>Department</th>
                <th>Programme</th>
                <th>Old Programme Type</th>
                <th>New Programme Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lists as $data)
                @php
                    $applicant = $data->applicant;
                @endphp
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $applicant->full_name }}</td>
                    <td>{{ $data->initial_appno }}</td>
                    <td>{{ $data->new_appno }}</td>
                    <td>{{ $applicant->faculty_name }}</td>
                    <td>{{ $applicant->department_name }}</td>
                    <td>{{ $applicant->programme_name }}</td>
                    <td>{{ PROG_TYPES[$data->initial_prog_type] }}</td>
                    <td>{{ PROG_TYPES[$data->new_prog_type] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-danger text-center">
                        No record found . . . !
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
