<table>
    <thead>
        <tr>
            <th><b>S/N</b></th>
            <th>Form No.</th>
            @switch($prog_id)
                @case(1)
                    <th>Jamb No.</th>
                @break

                @case(2)
                    <th>ND Matric No.</th>
                @break

                @default
                    <th>Jamb No. / ND Matric No.</th>
            @endswitch
            <th>Fullname</th>
            <th>Department</th>
            <th>Course</th>
            <th>Programme</th>
            <th>Programme Type</th>
            <th>Phone</th>
            <th>Email</th>
            <th>OLevels</th>
            @if ($prog_id != 2)
                <th>JAMB Score</th>
            @endif
            <th>Submit Status</th>
            <th>Admission Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applicants as $applicant)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $applicant->app_no }}</td>
                @switch($applicant->prog_id)
                    @case(1)
                        <td>{{ $applicant->regno }}</td>
                    @break

                    @case(2)
                        <td>{{ $applicant->nd_matric_no }}</td>
                    @break

                    @default
                        <td>{{ $applicant->regno }}</td>
                @endswitch
                <td>{{ $applicant->full_name }}</td>
                <td>{{ $applicant->department_name }}</td>
                <td>{{ $applicant->course_name }}</td>
                <td>{{ $applicant->programme_name }}</td>
                <td>{{ $applicant->programme_type }}</td>
                <td>{{ $applicant->student_mobiletel }}</td>
                <td>{{ $applicant->student_email }}</td>
                <td>{{ $applicant->olevels_string }}</td>
                @if ($prog_id != 2)
                    <td>{{ $applicant->jambs_point }}</td>
                @endif
                <td>{{ SUBMIT_STATUS[$applicant->submit_status ? $applicant->submit_status : 0] }}</td>
                <td>{{ ADM_STATUS[$applicant->adm_status ? $applicant->adm_status : 0] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
