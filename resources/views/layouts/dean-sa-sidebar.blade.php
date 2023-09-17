
{{-- Students' Statistics --}}
<li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#payment-history-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bar-chart"></i><span>Statistics</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="payment-history-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="{{ route('dean-sa.students.registered') }}">
                <i class="bi bi-circle"></i><span>Registered Students List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dean-sa.students.tuition') }}">
                <i class="bi bi-circle"></i><span>Tuition Fees List</span>
            </a>
        </li>
        <li>
            <a href="{{ route('dean-sa.students.statistics') }}">
                <i class="bi bi-circle"></i><span>Payments/Registration Stats</span>
            </a>
        </li>
    </ul>
</li>
