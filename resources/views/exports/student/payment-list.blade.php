<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registered Students' List</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>S/N</th>
                <th>SURNAME</th>
                <th>OTHERNAMES</th>
                <th>FACULTY</th>
                <th>DEPARTMENT</th>
                <th>MATRIC NUMBER</th>
                <th>FORM NUMBER</th>
                <th>LEVEL</th>
                <th>PROGRAMME TYPE</th>
                <th>TELEPHONE</th>
                <th>EMAIL</th>
                {{-- <th>TRANSACTION</th> --}}
                {{-- <th>AMOUNT</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $student->surname }}</td>
                    <td>{{ $student->othernames }}</td>
                    <td>{{ $student->faculty }}</td>
                    <td>{{ $student->department }}</td>
                    <td>'{{ $student->matric_number }}</td>
                    <td>{{ $student->form_number }}</td>
                    <td>{{ $student->level }}</td>
                    <td>{{ $student->programme_type }}</td>
                    <td>{{ $student->telephone }}</td>
                    <td>{{ $student->email }}</td>
                    {{-- <td>{{ $student->trans_name }}</td>
                    <td>{{ $student->trans_amount }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
