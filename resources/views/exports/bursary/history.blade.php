<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment History</title>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <th>Total Number</th>
                <td>{{ number_format($trans_action->number) }}</td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td>&#8358;{{ number_format($trans_action->total_amount, 2) }}</td>
            </tr>
            <tr></tr>
            <tr></tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th><b>S/N</b></th>
                <th>Matric Number</th>
                <th>Full Name</th>
                <th>Level</th>
                <th>Department</th>
                <th>Faculty</th>
                <th>Programme Type</th>
                <th>Transaction name</th>
                <th>State</th>
                <th>Amount</th>
                <th>Fee Type</th>
                <th>session</th>
                <th>Semester</th>
                <th>Transaction date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($transactions as $transaction)
                <tr>
                    <th>{{ $count++ }}</th>
                    <td>'{{ $transaction->appno }}</td>
                    <td>{{ $transaction->student->full_name }}</td>
                    <td>{{ $transaction->level->level_name }}</td>
                    <td>{{ $transaction->department->departments_name }}</td>
                    <td>{{ $transaction->faculty->faculties_name }}</td>
                    <td>{{ $transaction->progType->programmet_name }}</td>
                    <td>{{ $transaction->trans_name }}</td>
                    <td>{{ $transaction->state ? $transaction->state->state_name : '' }}</td>
                    <td>{{ $transaction->trans_amount }}</td>
                    <td>{{ $transaction->trans_name }}</td>
                    <td>{{ $transaction->trans_session->session }}</td>
                    <td>{{ $transaction->trans_semester }}</td>
                    <td>{{ $transaction->t_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-danger">
                        No record found!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
