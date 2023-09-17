<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admit Template</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th><b>S/N</b></th>
                <th><b>MATRIC NUMBER</b></th>
            </tr>
        </thead>
        <tbody>
            @php
            $count = 1;
            @endphp
            @forelse ($data as $applicant)
            <tr>
                <th>{{$count++}}</th>
                <td>{{$applicant->app_no}}</td>
            </tr>
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