<x-print-layout :title="'Graduating Running List'">
    <style>
        * {
            font-family: 'Times New Roman', Times, serif;
            text-transform: uppercase;
        }

        .body {
            font-size: 12px;
        }

        .broad-sheet {
            font-size: 10px !important;
        }

        .sign {
            padding: 0px 10px;
            border-top: 1px solid black;
            text-align: center;
        }

        .broad-sheet table,
        .broad-sheet th,
        .broad-sheet td {
            border: 2px solid;
            border-collapse: collapse;
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
                <div class="col-6">
                    <div class="h6">session: <span style="text-decoration:underline;">{{ $session }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h6">level: <span style="text-decoration:underline;">{{ $level->level_name }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h6">semester: <span
                            style="text-decoration:underline;">{{ SEMESTERS[$semester_id] }}</span></div>
                </div>
            </div>
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6 text-center">
                    <div class="h6" style="text-decoration: underline;">graduating running list</div>
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
                        @if ($key <= 4 || count(${$list}))
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
                $present_total = array_sum($total_results_presented) + count($suspended_list) + count($expelled_list) + count($withdrawal) + count($dead_list) + count($sick_list);
                ?>
                <tr>
                    <th>{{ $level->level_name }}</th>
                    <th>{{ $total_students }}</th>
                    @foreach ($total_results_presented as $item)
                        <th>{{ $item }}</th>
                    @endforeach
                    @foreach ($lists as $index => $list)
                        @if ($index <= 4 || count(${$list}))
                            <th>
                                {{ count(${$list}) }}
                                <?php
                                // $present_total += count(${$list});
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

        <div class="grades mt-3">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <table width="100%">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">remark summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <th>{{ str_replace('_', ' ', str_replace('_list', '', $list)) }}</th>
                                    <td>{{ count(${$list}) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @php
                                $sum = array_sum($total_results_presented) + count($suspended_list) + count($expelled_list) + count($withdrawal) + count($dead_list) + count($sick_list);
                                // foreach ($lists as $list) {
                                //     $sum += count(${$list});
                                // }
                            @endphp
                            <tr>
                                <th>Previous</th>
                                <td>{{ $total_results_presented[0] }}</td>
                            </tr>
                            <tr>
                                <th>SRO</th>
                                <td>{{ $outstanding }}</td>
                            </tr>
                            <tr>
                                <th>TOTAL NO</th>
                                <td>{{ $sum + $outstanding }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-4"></div>
            </div>
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
    <div class="page-break"></div>
    <div class="body">
        {{-- <div class="grades mt-3">
            <div class="row">
                <div class="col-4"></div>
                <div class="col-4">
                    <table width="100%">
                        <thead>
                            <tr class="text-center">
                                <th colspan="2">remark summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lists as $list)
                                <tr>
                                    <th>{{ str_replace('_', ' ', $list) }}</th>
                                    <td>{{ count(${$list}) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @php
                                $sum = 0;
                                foreach ($lists as $list) {
                                    $sum += count(${$list});
                                }
                            @endphp
                            <tr>
                                <th>TOTAL NO</th>
                                <td>{{ $sum }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="col-4"></div>
            </div>
        </div> --}}

        <div class="broad-sheet mt-5">
            @foreach ($lists as $list)
                @if (count(${$list}))
                    <div class="h5 text-center">{{ str_replace('_', ' ', $list) }}</div>
                    <table width="100%" class="mt-3 table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <th>s/n</th>
                                <th>matric number</th>
                                <th>full names</th>
                                <th colspan="3">present</th>
                                <th colspan="3">cumulative</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (${$list} as $data)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $data['matric_number'] }}</td>
                                    <td>{{ $data['name'] }}</td>
                                    <td>{{ $data['present_cp'] }}</td>
                                    <td>{{ $data['present_tu'] }}</td>
                                    <td>{{ $data['present_gp'] }}</td>
                                    <td>{{ $data['cummulative_cp'] }}</td>
                                    <td>{{ $data['cummulative_tu'] }}</td>
                                    <td>{{ $data['cummulative_gp'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
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
            {{-- PAGE <span class="page-number"></span> --}}
        </div>
    </div>
</x-print-layout>
