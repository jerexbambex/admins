

{{-- Students --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Courses</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="courses-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('lecturer.courses')}}">
                <i class="bi bi-circle"></i><span>Course Assigned</span>
            </a>
        </li>
    </ul>
</li>

{{-- Results --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#results-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Results</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="results-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('lecturer.result.upload-view')}}">
                <i class="bi bi-circle"></i><span>Upload Result</span>
            </a>
        </li>
        <li>
            <a href="{{route('lecturer.result.view')}}">
                <i class="bi bi-circle"></i><span>View/Edit Results</span>
            </a>
        </li>
    </ul>
</li>