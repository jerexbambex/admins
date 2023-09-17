<x-print-layout :title="'Semester Result'" :landscape="'true'">
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
            text-transform: uppercase;
        }

        .body {
            font-size: 11px;
        }

        .broad-sheet,
        .summary {
            font-size: 11px !important;
        }

        .broad-sheet table,
        .broad-sheet th,
        .broad-sheet td:not(.ignore) {
            border: 2px solid;
            border-collapse: collapse;
        }

        .ignore {
            border: none !important;
        }

        .add-top {
            border-top: 2px solid !important;
        }

        .remove-left {
            border-left: none !important;
        }

        .sign {
            padding: 0px 10px;
            border-top: 1px solid black;
            text-align: center;
        }

        .remarks {
            margin-top: 100px;
        }

        /* table{
            margin: 0!important;
        } */

        @media print {
            .page-break {
                page-break-after: always;
            }

            /* @page {
                size: auto;
                margin: 10px;
            } */

            div.footer {
                position: fixed;
                bottom: -5px;
                left: 0;
                right: 0;

                counter-reset: page;
            }

            .page-number:after {
                counter-increment: page;
                content: counter(page);
            }
        }

        @media screen {
            div.footer {
                display: none;
            }
        }

        .footer {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            font-weight: bold;
        }

        .heading {
            display: flex;
        }

        .heading-image {
            justify-content: center;
            text-align: center;
        }

        .heading-image img {
            height: 100px;
            width: 100px;
        }

        .heading .content {
            width: 100%;
        }

        .course-list th,
        .course-list td,
        .grades th,
        .grades td {
            padding: 0 15px;
        }
    </style>
    <div class="header">
        <div class="heading">
            <div class="heading-image">
                <img src="{{ asset('logo.jpeg') }}" alt="School Logo">
            </div>
            <div class="content text-center align-content-center align-item-center justify-content-center">
                <div class="h2" style="font-family: 'Arial Black'">THE POLYTECHNIC, IBADAN</div>
                <div class="h6" style="text-decoration:underline; font-weight: 600;">FACULTY OF
                    {{ $faculty->faculties_name }}</div>
                <div class="h6" style="text-decoration:underline; font-weight: 600;">DEPARTMENT OF
                    {{ $department->departments_name }}</div>
            </div>
        </div>
        <div class="text-justify">
            <div class="row">
                <div class="col-6">
                    <div class="h6">option: <span
                            style="text-decoration:underline;">{{ $option->programme_option }}</span></div>
                </div>
                <div class="col-3">
                    <div class="h6">session: <span style="text-decoration:underline;">{{ $session }}</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="h6">level: <span style="text-decoration:underline;">{{ $level->level_name }}</span>
                    </div>
                </div>
                <div class="col-9 text-center">
                    <div class="h6">
                        <span style="text-decoration:underline;">semester result </span>
                        &nbsp; - &nbsp;
                        <span style="text-decoration:underline;">{{ SEMESTERS[$semester_id] }}</span>
                    </div>
                </div>
                <div class="col-3">
                    @if ($hasPresentation)
                        <div class="h6">bos no:
                            <span style="text-decoration: underline;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{ $bos_number }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                        </div>
                    @else
                        <div class="h6">bos no: ....................................</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="h6">
                        <span style="text-decoration:underline;">presentation&nbsp;&nbsp; -
                            &nbsp;&nbsp;{{ $presentation }}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
    <div class="body">
        <div class="course-list">
            <table>
                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $course->thecourse_code }}</td>
                            <td>{{ $course->thecourse_title }}</td>
                            <td>{{ number_format($course->thecourse_unit, 2) }}</td>
                            <td>{{ $course->thecourse_cat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="grades mt-3">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <table>
                        <thead>
                            <tr>
                                <th>score grade</th>
                                <th>grading range</th>
                                <th>points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (GRADES as $key => $grade)
                                <tr>
                                    <td>{{ $key }}</td>
                                    @if (is_array($grade))
                                        <td>{{ sprintf('%s - %s', current($grade), end($grade)) }}%</td>
                                    @else
                                        <td>{{ $grade }}</td>
                                    @endif
                                    <td>{{ number_format(POINTS[$key], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="broad-sheet">
            <table width="100%" class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">S/N</th>
                        <th rowspan="2">matric no. / names</th>
                        @foreach ($courses as $course)
                            <th>{{ $course->thecourse_code }}</th>
                        @endforeach
                        <th rowspan="2">outstanding courses</th>
                        <th colspan="3">previous</th>
                        <th colspan="3">present</th>
                        <th colspan="3">cumulative</th>
                        <th rowspan="2">remark</th>
                    </tr>
                    <tr>
                        @foreach ($courses as $course)
                            <th>{{ "$course->thecourse_unit/$course->thecourse_cat" }}</th>
                        @endforeach
                        <th>ttnu</th>
                        <th>ttcp</th>
                        <th>cgpa</th>
                        <th>tnu</th>
                        <th>tcp</th>
                        <th>gpa</th>
                        <th>tnu</th>
                        <th>tcp</th>
                        <th>gpa</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @foreach ($students_list as $student)
                        {{-- @if ($student->hasCourseReg($session, $semester_id, $level_id) && $student->status == 'active') --}}
                        <tr>
                            <td rowspan="2">{{ $count++ }}</td>
                            <td rowspan="2">{{ $student['matric_no'] }}<br>{{ $student['full_name'] }}</td>
                            @foreach ($student['results'] as $r)
                                <td class="ignore add-top">{{ $r }}</td>
                            @endforeach
                            {{-- other courses --}}
                            <td rowspan="2">
                                {{ $student['outstanding_courses'] }}
                            </td>
                            {{-- Previous --}}
                            @foreach ($student['previous'] as $key => $prev)
                                <td class="<?= $key < count($student['previous']) - 1 ? 'ignore' : 'remove-left' ?>">
                                    {{ $prev }}</td>
                            @endforeach

                            {{-- Present --}}
                            @foreach ($student['present'] as $key2 => $pres)
                                <td class="<?= $key2 < count($student['present']) - 1 ? 'ignore' : 'remove-left' ?>">
                                    {{ $pres }}</td>
                            @endforeach


                            {{-- Cumulative --}}
                            @foreach ($student['cumulative'] as $key3 => $cumulative)
                                <td class="<?= $key3 < count($student['cumulative']) - 1 ? 'ignore' : 'remove-left' ?>">
                                    {{ $cumulative }}</td>
                            @endforeach

                            {{-- Remark --}}
                            <td rowspan="2">{{ $student['remarks'] }}</td>
                        </tr>
                        <tr>
                            @foreach ($student['grades'] as $g)
                                <td class="ignore">{{ $g }}</td>
                            @endforeach
                            <td colspan="9">
                                @foreach ($student['units'] as $key => $u)
                                    {{ "$key = $u" }},
                                @endforeach
                            </td>
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="remarks">
            <div class="row">
                <div class="col">
                    <div class="sign">
                        H.O.D's sign
                    </div>
                </div>
                <div class="col">
                    <div class="sign">
                        dean's sign
                    </div>
                </div>
                <div class="col">
                    <div class="sign">
                        rector's sign
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="footer">
        <div class="item">
            {{ sprintf('%s : IBA-%s-%s-%s-%s-%s-%s SEMESTER :%s', PROG_TYPE_ENUM[$prog_type_id], $level->level_name, $department->departments_code, $faculty->fcode, $department->departments_code, $set, $session, $semester_id) }}
        </div>
        <div class="item">
            PRINTED ON {{ date('d/m/y h:i:s A') }}
        </div>
        <div class="item">
            @if (Route::is('hod.results.semester-result'))
                COORDINATOR'S COPY - {{ $presentation }}
            @elseif(Route::is('hod.results.semester-result-vetter'))
                VETTER'S COPY - {{ $presentation }}
            @elseif(Route::is('hod.results.semester-result-bos'))
                B.O.S' COPY - {{ $presentation }}
            @endif
            {{-- PAGE <span class="page-number"></span> --}}
        </div>
    </div>
    <div class="page-break"></div>
    <div class="header">
        <div class="heading">
            <div class="heading-image">
                <img src="{{ asset('logo.jpeg') }}" alt="School Logo">
            </div>
            <div class="content text-center align-content-center align-item-center justify-content-center">
                <div class="h2" style="font-family: 'Arial Black'">THE POLYTECHNIC, IBADAN</div>
                <div class="h6" style="text-decoration:underline; font-weight: 600;">FACULTY OF
                    {{ $faculty->faculties_name }}</div>
                <div class="h6" style="text-decoration:underline; font-weight: 600;">DEPARTMENT OF
                    {{ $department->departments_name }}</div>
            </div>
        </div>
        <div class="text-justify">
            <div class="row">
                <div class="col-6">
                    <div class="h6">option: <span
                            style="text-decoration:underline;">{{ $option->programme_option }}</span></div>
                </div>
                <div class="col-3">
                    <div class="h6">session: <span style="text-decoration:underline;">{{ $session }}</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="h6">level: <span style="text-decoration:underline;">{{ $level->level_name }}</span>
                    </div>
                </div>
                <div class="col-9 text-center">
                    <div class="h6">
                        <span style="text-decoration:underline;">result statistics </span>
                        &nbsp; - &nbsp;
                        <span style="text-decoration:underline;">{{ SEMESTERS[$semester_id] }}</span>
                    </div>
                </div>
                <div class="col-3">
                    @if ($hasPresentation)
                        <div class="h6">bos no:
                            <span style="text-decoration: underline;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{ $bos_number }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                        </div>
                    @else
                        <div class="h6">bos no: ....................................</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="h6">
                        <span style="text-decoration:underline;">presentation&nbsp;&nbsp; -
                            &nbsp;&nbsp;{{ $presentation }}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th rowspan="2">LEVEL</th>
                    <th rowspan="2">NUMBER OF STUDENTS</th>
                    <th colspan="2">STUDENTS RESULT PRESENTED</th>
                    @foreach ($lists as $key => $list)
                        @if ($key <= 4 || ${$list})
                            <th rowspan="2">{{ str_replace('_', ' ', str_replace('_list', '', $list)) }}</th>
                        @endif
                    @endforeach
                    <th rowspan="2">TOTAL</th>
                    <th rowspan="2" style="font-size: 12px!important;">NUMBER OF STUDENTS' RESULT OUTSTANDING (SRO)
                    </th>
                    <th rowspan="2">GRAND TOTAL</th>
                </tr>
                <tr>
                    <th>Previous</th>
                    <th>Present</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $present_total = array_sum($total_results_presented) + $suspended_list + $expelled_list + $withdrawal + $dead_list + $sick_list;
                ?>
                <tr>
                    <th>{{ $level->level_name }}</th>
                    <th>{{ $total_students }}</th>
                    @foreach ($total_results_presented as $item)
                        <th>{{ $item }}</th>
                    @endforeach
                    @foreach ($lists as $key => $list)
                        @if ($key <= 4 || ${$list})
                            <th>
                                {{ ${$list} }}
                                <?php
                                // $present_total += ${$list};
                                ?>
                            </th>
                        @endif
                    @endforeach
                    <?php
                    $outstanding = $total_students - $present_total;
                    ?>
                    <th>{{ $present_total }}</th>
                    <th>{{ $outstanding }}</th>
                    <th>{{ $present_total + $outstanding }}</th>
                </tr>
            </tbody>
        </table>

        <div class="row summary">
            <div class="col-4"></div>
            <div class="col-4">
                <table width="100%">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-uppercase">remark summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = array_sum($total_results_presented) + $suspended_list + $expelled_list + $withdrawal + $dead_list + $sick_list;
                        @endphp
                        @foreach ($lists as $key => $list)
                            <?php
                            $t = ${$list};
                            // $total += $t;
                            ?>
                            <tr>
                                <th class="text-uppercase">
                                    {{ str_replace('_', ' ', str_replace('_list', '', $list)) }}</th>
                                <td>{{ $t }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Previous</th>
                            <td>{{ $total_results_presented[0] }}</td>
                        </tr>
                        <tr>
                            <th>SRO</th>
                            <td>{{ $outstanding }}</td>
                        </tr>
                        <tr>
                            <th>total</th>
                            <td>{{ $total + $outstanding }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-4"></div>
        </div>


        <div class="remarks" style="font-size: ">
            <div class="row">
                <div class="col">
                    <div class="sign">
                        H.O.D's sign
                    </div>
                </div>
                <div class="col">
                    <div class="sign">
                        dean's sign
                    </div>
                </div>
                <div class="col">
                    <div class="sign">
                        rector's sign
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-print-layout>
