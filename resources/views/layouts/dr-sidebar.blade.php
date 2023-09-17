

{{-- Students --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-person-circle"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('dr.students')}}">
                <i class="bi bi-circle"></i><span>All Students</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{route('dr.student.course_reset')}}">
                <i class="bi bi-circle"></i><span>Course Reset</span>
            </a>
        </li> --}}
        <li>
            <a href="{{route('dr.portal')}}">
                <i class="bi bi-circle"></i><span>Portal Access</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{route('dr.student.lost_access')}}">
                <i class="bi bi-circle"></i><span>Lost Access</span>
            </a>
        </li> --}}
        <li>
            <a href="{{route('dr.student.multiple_aspirants')}}">
                <i class="bi bi-circle"></i><span>Duplicates</span>
            </a>
        </li>
        <li>
            <a href="{{route('dr.student.notifications')}}">
                <i class="bi bi-circle"></i><span>Notifications</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{ route('dr.payments.history.manual-hostel-payment') }}">
                <i class="bi bi-circle"></i><span>Manual Hostel Payment</span>
            </a>
        </li> --}}
    </ul>
</li>

{{-- Applicants --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#duplicates-account-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Applicants</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="duplicates-account-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('dr.upload_applicants_score')}}">
                <i class="bi bi-circle"></i><span>Applicants' Score</span>
            </a>
        </li>
        <li>
            <a href="{{route('dr.set_exam_dates')}}">
                <i class="bi bi-circle"></i><span>Exam Dates</span>
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
            <a href="{{route('dr.all_hods')}}">
                <i class="bi bi-circle"></i><span>All HODs</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{route('dr.course_structure')}}">
                <i class="bi bi-circle"></i><span>Course Structure</span>
            </a>
        </li> --}}
        <li>
            <a href="{{route('dr.dept_codes')}}">
                <i class="bi bi-circle"></i><span>Department Codes</span>
            </a>
        </li>
        <li>
            <a href="{{route('view_course_structure')}}">
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
            <a href="{{ route('dr.students.registered') }}">
                <i class="bi bi-circle"></i><span>Registered Students</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dr.students.tuition') }}">
                <i class="bi bi-circle"></i><span>Tuition Fees</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dr.students.statistics') }}">
                <i class="bi bi-circle"></i><span>Tuition Payments Stats</span>
            </a>
        </li>
        <li>
            <a href="{{route('dr.payments.history.count')}}">
                <i class="bi bi-circle"></i><span>History Count</span>
            </a>
        </li>
    </ul>
</li>