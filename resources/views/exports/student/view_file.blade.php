@php
    if(!$file_ref_id) return redirect()->route('dashboard');
    $eclearance = \App\Models\Eclearance::find($file_ref_id);
    $log_id = $eclearance->std_id;
    $student  = \App\Models\Student::with(['department', 'faculty', 'progType', 'programme'])->whereStdLogid($log_id)->first();
    // $applicant = $student->applicant_profile();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{"$student->matric_no ($eclearance->docname)"}}</title>

    <style>
        header, .header{
            display: flex;
            justify-content: space-between;
        }

        header img{
            width: 150px;
            height: 150px;
        }

        iframe{
            width: 100%;
            height: 100%;
        }
        body{
            margin: 0 auto;
            padding: 30px;
        }
        main{
            margin: 0 auto;
            width: 80%;
            text-align: center;
        }
        main img{
            width: 100vw!important;
            max-width: 100vw!important;
            height: 850px!important; 
            max-height: 850px!important;
        }
    </style>
</head>
<body>
    <header>
        <div class="header">
            <table border="1" cellpadding="10" cellspacing="0">
                <tbody>
                    <tr>
                        <th>ADMISSION</th>
                        <th>FORM NO.</th>
                    </tr>
                    <tr>
                        <td>{{$student->std_admyear}}</td>
                        <td>{{$student->matric_no}}</td>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>UTME NO.</th>
                    </tr>
                    <tr>
                        <td>{{$log_id}}</td>
                        <td>{{$student->jamb_no}}</td>
                    </tr>
                </tbody>
            </table>
            <table border="1" cellpadding="10" cellspacing="0">
                <tbody>
                    <tr>
                        <th>FULLNAME:</th>
                        <th colspan="2">{{$student->full_name}}</th>
                        <th>{{$student->gender}}</th>
                    </tr>
                    <tr>
                        <td>{{$student->marital_status}}</td>
                        <td>{{$student->student_mobiletel}}</td>
                        <td colspan="2">{{$student->student_email}}</td>
                    </tr>
                    <tr>
                        <th>STATE:</th>
                        <th>{{$student->state_name}}</th>
                        <th>PROGRAM:</th>
                        <th>{{$student->programme_name}}</th>
                    </tr>
                    <tr>
                        <th>LGA:</th>
                        <th>{{$student->lga_name}}</th>
                        <th>ADM. TYPE:</th>
                        <th>{{$student->programme_type_name}}</th>
                    </tr>
                    <tr>
                        <th>DEPT:</th>
                        <th>{{$student->department_name}}</th>
                        <th>FACULTY:</th>
                        <th>{{$student->faculty_name}}</th>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <img src="{{env('UPLOAD_PATH').$student->std_photo}}" alt="">
        </div>
    </header>
    <main>
        <h3>{{$eclearance->docname}}</h3>
        <img 
            src="{{env('STORAGE_URL').$eclearance->doc}}" 
        />
    </main>
</body>
</html>