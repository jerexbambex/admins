{{-- School --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#school-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-building"></i><span>School</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="school-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('rector.school_dates') }}">
                <i class="bi bi-circle"></i>
                <span>School Dates</span>
            </a>
        </li><!-- End School Dates Structure Nav -->

        <li>
            <a href="{{ route('view_course_structure') }}">
                <i class="bi bi-circle"></i>
                <span>Print Course Structure</span>
            </a>
        </li><!-- End Print Course Structure Nav -->
    </ul>
</li>

{{-- Students History--}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#std-history-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Students' History</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="std-history-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('rector.students.history', ['param'=>'payments'])}}">
                <i class="bi bi-circle"></i><span>Payments Report</span>
            </a>
        </li>
        <li>
            <a href="{{route('rector.students.history', ['param'=>'registered'])}}">
                <i class="bi bi-circle"></i><span>Registration Report</span>
            </a>
        </li>
    </ul>
</li>

{{-- Student Stats --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#std-stats-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Students' Stats / List</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="std-stats-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('rector.students.registered') }}">
                <i class="bi bi-circle"></i>
                <span>Registered Students</span>
            </a>
        </li><!-- End Registered Students Nav -->
        
        <li>
            <a href="{{ route('rector.students.tuition') }}">
                <i class="bi bi-circle"></i>
                <span>Students' Tuition Fees</span>
            </a>
        </li><!-- End Students Tuition Fees Nav -->
        
        <li>
            <a href="{{ route('rector.students.statistics') }}">
                <i class="bi bi-circle"></i>
                <span>Tuition Payments' Stats</span>
            </a>
        </li><!-- End Students Statistics Nav -->
    </ul>
</li>

{{-- Result Tracking --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#results-tracking-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Results Tracking</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-tracking-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('rector.results.assigned_submitted') }}">
                <i class="bi bi-circle"></i>
                <span>Assigned & Submission Tracking</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('rector.results.unassigned') }}">
                <i class="bi bi-circle"></i>
                <span>Unassigned Courses</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('rector.results.assigned_not_submitted') }}">
                <i class="bi bi-circle"></i>
                <span>Assigned, Yet to Submit</span>
            </a>
        </li>
    </ul>
</li>

{{-- Result Processing --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#results-processing-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-earmark-medical"></i><span>Results Processing</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-processing-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('rector.results.processing') }}">
                <i class="bi bi-circle"></i>
                <span>View Result</span>
            </a>
        </li>
    </ul>
</li>
