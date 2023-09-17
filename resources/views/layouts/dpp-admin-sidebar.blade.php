{{-- Applicants --}}

<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#applicant-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Applicants</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="applicant-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('dpp-admin.applicants.view') }}">
                <i class="bi bi-circle"></i>View Applicants</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dpp-admin.applicants.template') }}">
                <i class="bi bi-circle"></i>Admission Template</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dpp-admin.applicants.change_of_course_upload') }}">
                <i class="bi bi-circle"></i>Change of Course</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dpp-admin.applicants.change_prog_type') }}">
                <i class="bi bi-circle"></i>Change Programme Type</span>
            </a>
        </li>
    </ul>
</li>

{{-- Admitted Students --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#admitted-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Admit Students</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="admitted-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('dpp-admin.admitted.upload') }}">
                <i class="bi bi-circle"></i>Upload Students</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dpp-admin.admitted.students') }}">
                <i class="bi bi-circle"></i>Toggle Admitted Students</span>
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
            <a href="{{ route('dpp-admin.student.view_stds', ['dept_id' => 0, 'do_id' => 0, 'fac_id' => 0]) }}">
                <i class="bi bi-circle"></i>Students' Clearance</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dpp-admin.students') }}">
                <i class="bi bi-circle"></i>View Students' Data</span>
            </a>
        </li>

        <li>
            <a href="{{ route('dpp-admin.student.change-of-course') }}">
                <i class="bi bi-circle"></i>Change of Course</span>
            </a>
        </li>
    </ul>
</li>
