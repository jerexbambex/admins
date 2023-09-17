{{-- <!-- ======= Sidebar ======= --> --}}
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        @switch(auth()->user()->user_role())
            @case('rector')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.rector-sidebar')
            @break

            @case('dr')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.dr-sidebar')
            @break

            @case('director')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.director-sidebar')
            @break

            @case('lecturer')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.lecturer-sidebar')
            @break

            @case('hod')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.hod-sidebar')
            @break

            @case('bursary')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.bursary-sidebar')
            @break

            @case('admission')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('admission.student.view') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.admission-sidebar')
            @break

            {{-- @case('corrector')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.corrector-sidebar')
            @break --}}
            @case('cidm-user')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.cidm-user-sidebar')
            @break

            @case('cec-admin')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.cec-admin-sidebar')
            @break

            @case('dean-sa')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.dean-sa-sidebar')
            @break

            @case('faculty-dean')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.faculty-dean-sidebar')
            @break

            @case('dpp-admin')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.dpp-admin-sidebar')
            @break

            @case('revalidation-admin')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.revalidation-admin-sidebar')
            @break

            @case('exams-and-records')
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('exams-and-records.dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                @include('layouts.exams-and-records-sidebar')
            @break

            @default
                <li class="nav-item">
                    <a class="nav-link collapsed" href="{{ route('dashboard') }}">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li><!-- End Dashboard Nav -->
        @endswitch




        <li class="nav-heading">Pages</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('profile.show') }}">
                <i class="bi bi-person"></i>
                <span>Profile</span>
            </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <a class="nav-link collapsed" onclick="event.preventDefault(); this.closest('form').submit();"
                    href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-in-left"></i>
                    <span>Log Out</span>
                </a>
            </form>

        </li><!-- End Login Page Nav -->
    </ul>

</aside><!-- End Sidebar-->
