<style>
    * {
        font-size: 13px;
        font-family: 'Times New Roman', Times, serif;
        text-align: left;
    }

    .to-caps {
        text-transform: uppercase;
    }

    .to-bold {
        font-weight: bold;
    }

    .date {
        text-align: right;
    }

    .heading {
        text-align: center;
        font-size: 14px;
    }

    .letter {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 8px;
    }

    .signature {
        margin-top: 8px;
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

        <div class="letter">
            <div class="date">
                DATE: {{ date('j F Y') }}
            </div>

            <div class="info">
                <table border="0" cellspacing="2" cellpadding="6">
                    <tr>
                        <th>Matric No:</th>
                        <th>{{ $matric_number }}</th>
                    </tr>
                    <tr>
                        <th>Names</th>
                        <td class="to-caps">{{ str_replace(',', '', $full_name) }}</td>
                    </tr>
                    <tr>
                        <th>Faculty</th>
                        <td class="to-caps">{{ $faculty }}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td class="to-caps">{{ $department }}</td>
                    </tr>
                </table>
            </div>

            <div class="salutation">
                <b>Dear</b> <span class="to-caps">{{ $full_name }}</span>,
            </div>

            <div class="heading to-caps">
                notification of completion of {{ $programme }} programme in {{ $session }}
            </div>

            <div class="body">
                <p>
                    Having completed the course of study approved by the Board of Studies of The Polytechnic, Ibadan,
                    and
                    passed the prescribed examinations, I have the pleasure to inform you that you have satisfied the
                    requirements for the award of <span class="to-caps to-bold">{{ $programme }}</span> in <span
                        class="to-caps to-bold">{{ $course }}</span> with a grade of <span
                        class="to-caps to-bold">{{ $grade }}</span> with effect from <span class="to-bold">18th
                        December, {{ $session_end }}</span>.
                </p>
                <p>
                    As soon as all the details of your academic work are put together, your diploma certificate shall be
                    issued to you.
                </p>
                <p>Accept my congratulations.</p>
                <p>Yours faithfully,</p>

            </div>
            <div class="signature">
                <div class="to-caps to-bold">
                    mr. o.a olaoye <br />
                    deputy registrar (exams and records)
                </div>
                <div class="to-bold">
                    <i>For: Registrar</i>
                </div>
            </div>
        </div>
    @endforeach
</div>
