

{{-- School --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#hods-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-building"></i><span>School</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="hods-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('cidm-user.course_structure')}}">
                <i class="bi bi-circle"></i><span>Course Structure</span>
            </a>
        </li>
        <li>
            <a href="{{route('view_course_structure')}}">
                <i class="bi bi-circle"></i><span>Print Course Structure</span>
            </a>
        </li>
    </ul>
</li>
