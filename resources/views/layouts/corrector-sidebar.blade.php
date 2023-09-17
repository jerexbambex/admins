

{{-- Students --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#students-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('corrector.students')}}">
                <i class="bi bi-circle"></i><span>All Students</span>
            </a>
        </li>
        {{-- <li>
            <a href="{{route('corrector.student.course_reset')}}">
                <i class="bi bi-circle"></i><span>Course Reset</span>
            </a>
        </li> --}}
        {{-- <li>
            <a href="{{route('corrector.student.lost_access')}}">
                <i class="bi bi-circle"></i><span>Lost Access</span>
            </a>
        </li> --}}
    </ul>
</li>
