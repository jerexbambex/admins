<div class="content">
    @include('layouts.messages')
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Filter
            </div>

            <form wire:submit.prevent="filterParams">
                <div class="row">
                    <div class="col-md form-group">
                        <label>Programme</label>
                        <select class="form-control" wire:model="programme">
                            {{-- <option value="">All</option> --}}
                            @foreach ($programmes as $programme)
                                <option value="{{ $programme->programme_id }}">{{ $programme->programme_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Department Option</label>
                            <select wire:model="option" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($deptOptions as $deptOption)
                                    <option value="{{ $deptOption->do_id }}">{{ $deptOption->programme_option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label>Level</label>
                            <select wire:model="search_level" class="form-control">
                                <option value="">Select . . .</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>For Programme Type</label>
                            <select wire:model="for_cec" class="form-control">
                                <option value="0">FT & DPP</option>
                                <option value="1">CEC</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md">
                        <div class="form-group">
                            <label>Set</label>
                            <select wire:model="app_session" class="form-control">
                                {{-- <option >All</option> --}}
                                @foreach ($sessions as $set)
                                    <option>{{ $set->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Session</label>
                            <select wire:model="sch_session" class="form-control">
                                {{-- <option >All</option> --}}
                                @foreach ($sessions as $sess)
                                    <option>{{ $sess->session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label>Semester</label>
                            <select wire:model="search_semester" class="form-control">
                                {{-- <option >All</option> --}}
                                @foreach (SEMESTERS as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-block btn-outline-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                {{-- ALL COURSES ({{ sprintf('%s/%s', $app_session, (int) $app_session + 1) }}) --}}
                ALL COURSES ({{ "$app_session SET" }})
            </div>
            @php
                $total_courses_units = 0;
            @endphp

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="">
                        <tr>
                            <th>#</th>
                            <th>Course Name</th>
                            <th>Course Code</th>
                            <th>Course Unit</th>
                            <th>Course Type</th>
                            <th>Course Level</th>
                            <th>Course Semester</th>
                            @if ($param == 'assign')
                                <th>Is Assigned</th>
                                <th>Action</th>
                            @elseif($param == 'scoresheet')
                                <th>Sheet</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            @php
                                $total_courses_units += (int) $course->thecourse_unit;
                            @endphp
                            <tr>
                                <th>{{ $loop->iteration }}</th>
                                <td>{{ $course->thecourse_title }}</td>
                                <td>{{ $course->thecourse_code }}</td>
                                <td>{{ $course->thecourse_unit }}</td>
                                <td>{{ $course->thecourse_cat }}</td>
                                <td>{{ $course->level_text }}</td>
                                <td>{{ $course->semester }}</td>

                                @if ($param == 'assign')
                                    <td>
                                        @if ($course->isAssigned($app_session, $sch_session))
                                            <i class="badge bg-success">Assigned</i>
                                        @else
                                            <i class="badge bg-danger">No Lecturer Assigned</i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($course->isAssigned($app_session, $sch_session))
                                            <a target="_blank"
                                                href="{{ route('hod.assignCourses', ['course' => $course->thecourse_id]) }}"
                                                class="btn btn-warning btn-sm w-100 text-white">Assign New Lecturer</a>
                                        @else
                                            <a target="_blank"
                                                href="{{ route('hod.assignCourses', ['course' => $course->thecourse_id]) }}"
                                                class="btn btn-info btn-sm w-100 text-white">Assign Lecturer</button>
                                        @endif
                                    </td>
                                @elseif($param == 'scoresheet')
                                    <td>
                                        <a href="{{ route('hod.scoreSheet', ['course' => $course->thecourse_id]) }}"
                                            class="btn btn-info btn-sm w-100 text-white">Score Sheet</a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">
                                    <p class="text-danger text-center">
                                        No data found
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($courses != [])
                    {{ $courses->links() }}
                @endif
                <br>
                <b>Total Units: </b> {{ $total_courses_units }}
            </div>


        </div>

    </div>


</div>
