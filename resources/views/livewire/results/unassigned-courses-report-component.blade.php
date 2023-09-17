<div>
    <div class="card">
        <form wire:submit.prevent="filterReport">
            <div class="card-body">
                <div class="card-title">
                    Report Filter
                </div>

                <div class="form-body">
                    <div class="row">
                        @if (!auth()->user()->faculty_id)
                            <div class="form-group col-md">
                                <label for="faculty_id">Faculty</label>
                                <select wire:model="faculty_id" id="faculty_id" class="form-control">
                                    <option value="0">Select Faculty</option>
                                    @foreach ($faculties as $fac)
                                        <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="form-group col-md">
                            <label for="department_id">Department</label>
                            <select wire:model="department_id" id="department_id" class="form-control">
                                <option value="0">Select Department</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="programme_id">Programme</label>
                            <select wire:model="programme_id" id="programme_id" class="form-control">
                                <option value="0">Select Programme</option>
                                @foreach ($programmes as $prog)
                                    <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md">
                            <label for="option_id">Dept. Option</label>
                            <select wire:model.lazy="option_id" id="option_id" class="form-control">
                                <option value="0">Select Option</option>
                                @foreach ($options as $opt)
                                    <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="level_id">Level</label>
                            <select wire:model.lazy="level_id" id="level_id" class="form-control">
                                <option value="0">Select Level</option>
                                @foreach ($levels as $lvl)
                                    <option value="{{ $lvl->level_id }}">{{ $lvl->level_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="set_year">Set</label>
                            <select wire:model.lazy="set_year" id="set_year" class="form-control">
                                <option value="0">Select Set</option>
                                @foreach ($sessions as $set)
                                    <option>{{ $set->year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md">
                            <label for="session_year">Session</label>
                            <select wire:model.lazy="session_year" id="session_year" class="form-control">
                                <option value="0">Select Session</option>
                                @foreach ($sessions as $sess)
                                    <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="semester_id">Semester</label>
                            <select wire:model.lazy="semester_id" id="semester_id" class="form-control">
                                @foreach (SEMESTERS as $key => $sem)
                                    <option value="{{ $key }}">{{ $sem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md">
                            <label for="prog_type">Prog. Type</label>
                            <select wire:model.lazy="prog_type" id="prog_type" class="form-control">
                                @foreach ($programme_types as $pt)
                                    <option value="{{ $pt->programmet_id }}">{{ $pt->programmet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Report List - {{ $prog_type ? PROG_TYPES[$prog_type] : 'All' }}
            </div>

            @if (count($courses))
                @php
                    $course = $courses[0];
                    $hod = $course->department->hod();
                @endphp
                <table class="table table-bordered">
                    <tr>
                        <th>H.O.D's Name</th>
                        <th>{{ $hod->full_name }}</th>
                    </tr>
                    <tr>
                        <th>H.O.D's Mobile</th>
                        <th><a href="tel:{{ $hod->mobile }}">{{ $hod->mobile }}</a></th>
                    </tr>
                    <tr>
                        <th>H.O.D's Email</th>
                        <th><a href="tel:{{ $hod->email }}">{{ $hod->email }}</a></th>
                    </tr>
                    <tr>
                        <th>H.O.D's Staff ID</th>
                        <th>{{ $hod->staff_id }}</th>
                    </tr>
                </table>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Assign Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            if ($courses):
                                $page = $courses->currentPage();
                                $paginate = $courses->perPage();
                                $count = $page * $paginate - $paginate + 1;
                            endif;
                        @endphp
                        @forelse ($courses as $course)
                            <tr>
                                <th>{{ $count++ }}</th>
                                <td>{{ $course->thecourse_code }}</td>
                                <td>{{ $course->thecourse_title }}</td>
                                <td>
                                    <small class="badge bg-danger">Yet to assign</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    No record found . . . !
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (count($courses))
                {{ $courses->links() }}
            @endif
        </div>
    </div>

</div>
