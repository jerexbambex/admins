{{-- Student Stats --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#std-stats-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Students' Stats / List</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="std-stats-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('director.students.registered') }}">
                <i class="bi bi-circle"></i>
                <span>Registered Students</span>
            </a>
        </li>{{-- End Registered Students Nav --}}

        <li>
            <a href="{{ route('director.students.tuition') }}">
                <i class="bi bi-circle"></i>
                <span>Students' Tuition Fees</span>
            </a>
        </li>{{-- End Students Tuition Fees Nav --}}

        <li>
            <a href="{{ route('director.students.statistics') }}">
                <i class="bi bi-circle"></i>
                <span>Tuition Payments' Stats</span>
            </a>
        </li>{{-- End Students Statistics Nav --}}
    </ul>
</li>

{{-- School --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#school-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-building"></i><span>School</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="school-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('director.school.faculty-deans') }}">
                <i class="bi bi-circle"></i>
                <span>Faculty Deans</span>
            </a>
        </li>{{-- End Faculty Deans Nav --}}

        <li>
            <a href="{{ route('director.school.hods') }}">
                <i class="bi bi-circle"></i>
                <span>H.O.Ds</span>
            </a>
        </li>{{-- End H.O.Ds Nav --}}

        <li>
            <a href="{{ route('director.school.dept_codes') }}">
                <i class="bi bi-circle"></i>
                <span>Departmental Codes</span>
            </a>
        </li>{{-- End Departmental Codes Nav --}}

        <li>
            <a href="{{ route('director.school.graduating_requirements') }}">
                <i class="bi bi-circle"></i>
                <span>Graduating Requirements</span>
            </a>
        </li>{{-- End Graduating Requirements Nav --}}

        <li>
            <a href="{{ route('director.school.school_dates') }}">
                <i class="bi bi-circle"></i>
                <span>School Dates</span>
            </a>
        </li>{{-- End School Dates Structure Nav --}}

        <li>
            <a href="{{ route('director.school.course_structure') }}">
                <i class="bi bi-circle"></i>
                <span>Course Structure</span>
            </a>
        </li>{{-- End Course Structure Nav --}}

        <li>
            <a href="{{ route('view_course_structure') }}">
                <i class="bi bi-circle"></i>
                <span>Print Course Structure</span>
            </a>
        </li>{{-- End Print Course Structure Nav --}}
    </ul>
</li>

{{-- Student Penalty --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#student-penalty-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-building"></i><span>Student Penalties</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="student-penalty-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('director.student-penalties.suspension') }}">
                <i class="bi bi-circle"></i>
                <span>Suspension</span>
            </a>
        </li>{{-- End Suspension Nav --}}
        <li>
            <a href="{{ route('director.student-penalties.indefinite-suspension') }}">
                <i class="bi bi-circle"></i>
                <span>Indefinite Suspension</span>
            </a>
        </li>{{-- End Indefinite Suspension Nav --}}
        <li>
            <a href="{{ route('director.student-penalties.expulsion') }}">
                <i class="bi bi-circle"></i>
                <span>Expulsion</span>
            </a>
        </li>{{-- End Expulsion Nav --}}
        <li>
            <a href="{{ route('director.student-penalties.sick') }}">
                <i class="bi bi-circle"></i>
                <span>Sick/Deferment</span>
            </a>
        </li>{{-- End Sick Nav --}}
        <li>
            <a href="{{ route('director.student-penalties.death') }}">
                <i class="bi bi-circle"></i>
                <span>Death</span>
            </a>
        </li>{{-- End Death Nav --}}
        <li>
            <a href="{{ route('director.student-penalties.reinstatement-from-indefinite') }}">
                <i class="bi bi-circle"></i>
                <span>Reinstate From Indefinite Penalty</span>
            </a>
        </li>{{-- End Reinstate from Indefinite suspension Nav --}}
    </ul>
</li>

{{-- Result Tracking --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#results-tracking-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Results Tracking</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-tracking-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('director.results.assigned_submitted') }}">
                <i class="bi bi-circle"></i>
                <span>Assigned & Submission Tracking</span>
            </a>
        </li>

        <li>
            <a href="{{ route('director.results.unassigned') }}">
                <i class="bi bi-circle"></i>
                <span>Unassigned Courses</span>
            </a>
        </li>

        <li>
            <a href="{{ route('director.results.assigned_not_submitted') }}">
                <i class="bi bi-circle"></i>
                <span>Assigned, Yet to Submit</span>
            </a>
        </li>
    </ul>
</li>

{{-- Result Processing --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#results-processing-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-earmark-medical"></i><span>Results Processing</span><i
            class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-processing-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('director.results.processing') }}">
                <i class="bi bi-circle"></i>
                <span>View Result</span>
            </a>
        </li>
        <li>
            <a href="{{ route('director.results.revalidation') }}">
                <i class="bi bi-circle"></i>
                <span>Revalidation</span>
            </a>
        </li>
    </ul>
</li>
