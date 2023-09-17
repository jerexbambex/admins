{{-- Lecturers  --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#lecturers-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Lecturers</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="lecturers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('hod.lecturer', ['action' => 'add']) }}">
                <i class="bi bi-circle"></i><span>Add New Lecturer</span>
            </a>
        </li>
        <li>
            <a href="{{ route('hod.lecturers') }}">
                <i class="bi bi-circle"></i><span>View All Lecturers</span>
            </a>
        </li>
    </ul>
</li>

{{-- Courses Routes --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Courses</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="courses-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('hod.courses', ['param' => 'assign']) }}">
                <i class="bi bi-circle"></i><span>Assign Courses to Lecturer</span>
            </a>
        </li>
        <li>
            <a href="{{ route('hod.courses', ['param' => 'scoresheet']) }}">
                <i class="bi bi-circle"></i><span>Download Scoresheet</span>
            </a>
        </li>
        <li>
            <a href="{{ route('hod.courses', ['param' => 'view']) }}">
                <i class="bi bi-circle"></i><span>View All Courses</span>
            </a>
        </li>
    </ul>
</li>

<li class="nav-heading">Result Processing</li>
{{-- Results Routes --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#results-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Results</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('hod.results.download') }}">
                <i class="bi bi-circle"></i><span>Download Results</span>
            </a>
            <a href="{{ route('hod.results.view') }}">
                <i class="bi bi-circle"></i><span>View/Edit Results</span>
            </a>
            <a href="{{ route('hod.results.submission') }}">
                <i class="bi bi-circle"></i><span>Result Submission</span>
            </a>
        </li>
    </ul>
</li>
{{-- Final Results Routes --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#final-results-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Final Results</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="final-results-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('hod.results.final.vetted') }}">
                <i class="bi bi-circle"></i><span>Vetted Copy</span>
            </a>
            <a href="{{ route('hod.results.final.bos') }}">
                <i class="bi bi-circle"></i><span>B.O.S' Copy</span>
            </a>
            <a href="{{ route('hod.results.final.graduating') }}">
                <i class="bi bi-circle"></i><span>Graduating</span>
            </a>
        </li>
    </ul>
</li>
{{-- 
<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('hod.dept_codes') }}">
        <i class="bi bi-braces"></i>
        <span>Departmental Codes</span>
    </a>
</li> End Departmental Codes Nav --}}

<li class="nav-heading">Config</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('hod.graduating_requirements') }}">
        <i class="bi bi-list"></i>
        <span>Graduating Requirements</span>
    </a>
</li>{{-- End Graduating Requirements Nav --}}

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('hod.students.registered') }}">
        <i class="bi bi-list"></i>
        <span>Registered Students</span>
    </a>
</li>{{-- End Registered Students Nav --}}

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('hod.students.tuition') }}">
        <i class="bi bi-list"></i>
        <span>Students Tuition Fees</span>
    </a>
</li>{{-- End Students Tuition Fees Nav --}}

<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('hod.students.statistics') }}">
        <i class="bi bi-bar-chart"></i>
        <span>Tuition Payments Stats</span>
    </a>
</li>{{-- End Students Statistics Nav --}}
