<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TUITION FEES PAYMENT STATISTICS</title>
</head>

<body>
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Faculty</th>
                <th>Department</th>
                <th>Dept. Option</th>
                <th>Level</th>
                <th>Prog. Type</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $stat)
                <tr>
                    <th>{{ $loop->iteration }}</th>
                    <td>{{ $stat->faculty ?? '' }}</td>
                    <td>{{ $stat->department ?? '' }}</td>
                    <td>{{ $stat->option_name ?? '' }}</td>
                    <td>{{ $stat->level_name ?? '' }}</td>
                    <td>{{ $stat->programme_type ?? '' }}</td>
                    <td>{{ $stat->total_count ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
