<style>
    .container {
        width: 100%;
        text-transform: uppercase;
    }

    * {
        text-align: center;
    }

    @media print {
        .page-break {
            page-break-after: always;
        }
    }
</style>
<div class="container">
    @foreach ($students as $key => $student)
        @if (!$key)
            <div class="page-break"></div>
        @endif
        @php
            extract($student);
        @endphp

        <div class="body">
            <div style="margin-top: 1.45in">{{ $course }}</div>
            <div style="margin-top: .4in">{{ $full_name }}</div>
            <div style="margin-top: .4in">{{ $grade }}</div>
            <div style="margin-top: .45in">{{ $course }}</div>
            <div style="margin-top: .3in">18 December {{ $session_end }}</div>
        </div>
    @endforeach
</div>
