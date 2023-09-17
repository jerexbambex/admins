<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Exam Dates</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>FORM NUMBER</th>
                <th>DEPARTMENT</th>
                <th>FACULTY</th>
                <th>EXAM DATE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $student)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$student->full_name}}</td>
                    <td>{{"$student->student_email"}}</td>
                    <td>{{$student->app_no}}</td>
                    <td>{{$student->department_name}}</td>
                    <td>{{$student->faculty_name}}</td>
                    <td>{{modifiedDate($student->exam_date)}}</td>
                </tr>
            @endforeach
            <tr></tr>
        </tbody>
    </table>
</body>
</html>