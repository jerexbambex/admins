{{-- Student Stats --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#std-stats-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Students' Stats / List</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="std-stats-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('faculty-dean.students.registered') }}">
                <i class="bi bi-circle"></i>
                <span>Registered Students</span>
            </a>
        </li><!-- End Registered Students Nav -->
    </ul>
</li>

{{-- Result Tracking --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#results-tracking-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-clock-history"></i><span>Results Tracking</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-tracking-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('faculty-dean.results.assigned_submitted') }}">
                <i class="bi bi-circle"></i>
                <span>Assigned & Submission Tracking</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('faculty-dean.results.unassigned') }}">
                <i class="bi bi-circle"></i>
                <span>Unassigned Courses</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('faculty-dean.results.assigned_not_submitted') }}">
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
            <a href="{{ route('faculty-dean.results.processing') }}">
                <i class="bi bi-circle"></i>
                <span>View Result</span>
            </a>
        </li>
    </ul>
</li>
