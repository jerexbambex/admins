

{{-- Students --}}
<li class="nav-item"> 
    <a class="nav-link collapsed" data-bs-target="#courses-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-text"></i><span>Students</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="courses-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{route('bursary.students.payment')}}">
                <i class="bi bi-circle"></i><span>All Payments</span>
            </a>
        </li>
    </ul>
</li>

{{-- <!-- Manual Payment --> --}}
<li class="nav-item">
    <a class="nav-link collapsed" href="{{ route('bursary.manual-hostel-payment') }}">
        <i class="bi bi-file-text"></i>
        <span>Manual Hostel Payment</span>
    </a>
</li>