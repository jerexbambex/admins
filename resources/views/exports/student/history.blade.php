<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ucfirst($param)." Students"}}</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th><b>ID</b></th>
                <th><b>MATRIC NUMBER</b></th>
                <th><b>FORM NUMBER</b></th>
                <th><b>FULL NAME</b></th>
                <th><b>GENDER</b></th>
                <th><b>LEVEL</b></th>
                <th><b>DEPARTMENT</b></th>
                <th><b>FACULTY</b></th>
                <th><b>PROGRAMME TYPE</b></th>
                <th><b>ADMISSION YEAR</b></th>
                @php
                    $level = \App\Models\Level::find($level_id)->level_name;
                @endphp
                <th><b>{{$level}} - 1</b></th>
                <th><b>{{$level}} - 2</b></th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($data as $student)
                <tr>
                    <th>{{$count++}}</th>
                    <td>{{$student->matric_no}}</td>
                    <td>{{$student->matset}}</td>
                    <td>{{$student->full_name}}</td>
                    <td>{{$student->gender}}</td>
                    <td>{{$student->level->level_name}}</td>
                    <td>{{$student->department->departments_name}}</td>
                    <td>{{$student->faculty->faculties_name}}</td>
                    <td>{{$student->progType->programmet_name}}</td>
                    <td>{{$student->std_admyear}}</td>
                    @if($param == 'registered')
                        <td>
                            @if($student->hasCourseReg($adm_year, 1, $level_id))
                            <span class="badge bg-success">Registered</span>
                            @else
                            <span class="badge bg-danger">Not Registered</span>
                            @endif
                        </td>
                        <td>
                            @if($student->hasCourseReg($adm_year, 2, $level_id))
                            <span class="badge bg-success">Registered</span>
                            @else
                            <span class="badge bg-danger">Not Registered</span>
                            @endif
                        </td>
                    @elseif($param == 'payments')
                        <td>
                            @if($student->hasPayment($adm_year, 1, $level_id))
                            <span class="badge bg-success">Paid</span>
                            @else
                            <span class="badge bg-danger">Not Paid</span>
                            @endif
                        </td>
                        <td>
                            @if($student->hasPayment($adm_year, 2, $level_id))
                            <span class="badge bg-success">Paid</span>
                            @else
                            <span class="badge bg-danger">Not Paid</span>
                            @endif
                        </td>
                    @endif
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