<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Applicants</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th><b>S/N</b></th>
                <th class="th-lg">Form No.</th>
                <th class="th-lg">Jamb No.</th>
                <th class="th-lg">Fullname</th>
                <th class="th-lg">Department</th>
                <th class="th-lg">Programme</th>
                <th class="th-lg">Programme Type</th>
                <th class="th-lg">Submit Status</th>
                <th class="th-lg">Admission Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $applicant)
            <tr>
                <th>{{$loop->iteration}}</th>
                <td>{{$applicant->app_no}}</td>
                <td>{{$applicant->jamb_detail ? $applicant->jamb_detail->jambno : $applicant->app_no}}</td>
                <td>{{$applicant->full_name}}</td>
                <td>{{$applicant->dept_option->department->departments_name}}</td>
                <td>{{$applicant->programme->programme_name}}</td>
                <td>{{$applicant->progType->programmet_name}}</td>
                <td>{{SUBMIT_STATUS[$applicant->std_custome9]}}</td>
                <td>{{ADM_STATUS[$applicant->adm_status]}}</td>
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