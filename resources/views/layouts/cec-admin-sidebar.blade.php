<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#applicant-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Admissions</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="applicant-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('cec-admin.applicants.view') }}">
                <i class="bi bi-circle"></i>View Applicants</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.applicants.change_prog_type') }}">
                <i class="bi bi-circle"></i>Applicants' Migration</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.applicants.template') }}">
                <i class="bi bi-circle"></i>Admission Template</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.applicants.change_of_course_upload') }}">
                <i class="bi bi-circle"></i>Change of Course</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.admitted.students') }}">
                <i class="bi bi-circle"></i>Admission List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.admitted.upload') }}">
                <i class="bi bi-circle"></i>Upload Students</span>
            </a>
        </li>
    </ul>
</li>

{{-- Students --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#student-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="student-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('cec-admin.student.view_stds', ['dept_id' => 0, 'do_id' => 0, 'fac_id' => 0]) }}">
                <i class="bi bi-circle"></i>Students' Clearance</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.student.all') }}">
                <i class="bi bi-circle"></i>View Students' Data</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.student.change-of-course') }}">
                <i class="bi bi-circle"></i>Change of Course</span>
            </a>
        </li>
    </ul>
</li>


{{-- School --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#hods-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-building"></i><span>School</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="hods-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('cec-admin.course_structure') }}">
                <i class="bi bi-circle"></i><span>Course Structure</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{ route('cec-admin.dept_codes') }}">
                <i class="bi bi-circle"></i><span>Department Codes</span>
            </a>
        </li> --}}
        <li>
            <a href="{{ route('view_course_structure') }}">
                <i class="bi bi-circle"></i><span>Print Course Structure</span>
            </a>
        </li>
    </ul>
</li>

{{-- Students' Statistics --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#payment-history-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Statistics</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="payment-history-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('cec-admin.students.registered') }}">
                <i class="bi bi-circle"></i><span>Registered Students</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.students.tuition') }}">
                <i class="bi bi-circle"></i><span>Tuition Fees</span>
            </a>
        </li>
        <li>
            <a href="{{ route('cec-admin.students.statistics') }}">
                <i class="bi bi-circle"></i><span>Tuition Payments Stats</span>
            </a>
        </li>
    </ul>
</li>
