<div>
    <div class="content">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    {{ ucfirst($param) . ' Students (Filter)' }}
                </h5>

                <div class="row">
                    <div class="col-md form-group">
                        <label>Faculty</label>
                        <select class="form-control" wire:model="faculty_id">
                            <option value="0">All</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Department</label>
                        <select class="form-control" wire:model="department_id">
                            <option value="0">All</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->departments_id }}">{{ $department->departments_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Dept. Option</label>
                        <select class="form-control" wire:model.lazy="opt_id">
                            <option value="0">All</option>
                            @foreach ($options as $opt)
                                <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md form-group">
                        <label>Programme</label>
                        <select class="form-control" wire:model="prog_id">
                            @foreach (PROGRAMMES as $key => $programme)
                                <option value="{{ $key }}">{{ $programme }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Level</label>
                        <select class="form-control" wire:model="level_id">
                            @if ($prog_id)
                                @foreach ($levels as $lvl)
                                    <option value="{{ $lvl->level_id }}">{{ $lvl->level_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Programme Type</label>
                        <select class="form-control" wire:model.lazy="prog_type_id">
                            <option value="0">All</option>
                            @foreach (PROG_TYPES as $key => $progType)
                                <option value="{{ $key }}">{{ $progType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md form-group">
                        <label>Session</label>
                        <select class="form-control" wire:model.lazy="adm_year">
                            <option value="0">All</option>
                            @foreach ($sch_sessions as $sess)
                                <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">
                <form wire:submit.prevent="download">
                    <button class="btn btn-sm btn-primary" type="submit">Download</button>
                </form>
                <div class="form-body">
                    <div class="form-group">
                        <button class="btn btn-sm btn-primary float-end">Search</button>
                        <input type="text" class="float-end" placeholder="Search . . ." wire:model.lazy="search_param">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="text-center">
                            <th>#</th>
                            <th>Matric Number</th>
                            <th>Form Number</th>
                            <th>Full Name</th>
                            <th>Gender</th>
                            <th>Level</th>
                            <th>Department</th>
                            <th>Faculty</th>
                            <th>Programme Type</th>
                            <th>Admission Year</th>
                            @php
                                $level = \App\Models\Level::find($level_id)->level_name;
                            @endphp
                            <th>{{ $level }} - 1</th>
                            <th>{{ $level }} - 2</th>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                                if ($students):
                                    $page = $students->currentPage();
                                    $paginate = PAGINATE_SIZE;
                                    $count = $page * $paginate - $paginate + 1;
                                endif;
                            @endphp
                            @forelse ($students as $student)
                                <tr>
                                    <th>{{ $count++ }}</th>
                                    <td>{{ $student->matric_no }}</td>
                                    <td>{{ $student->matset }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>{{ $student->gender }}</td>
                                    <td>{{ $student->level->level_name }}</td>
                                    <td>{{ $student->department->departments_name }}</td>
                                    <td>{{ $student->faculty->faculties_name }}</td>
                                    <td>{{ $student->progType->programmet_name }}</td>
                                    <td>{{ $student->std_admyear }}</td>
                                    @if ($param == 'registered')
                                        <td>
                                            @if ($student->hasCourseReg($adm_year, 1, $level_id))
                                                <span class="badge bg-success">Registered</span>
                                            @else
                                                <span class="badge bg-danger">Not Registered</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($student->hasCourseReg($adm_year, 2, $level_id))
                                                <span class="badge bg-success">Registered</span>
                                            @else
                                                <span class="badge bg-danger">Not Registered</span>
                                            @endif
                                        </td>
                                    @elseif($param == 'payments')
                                        <td>
                                            @if ($student->hasPayment($adm_year, 1, $level_id))
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger">Not Paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($student->hasPayment($adm_year, 2, $level_id))
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger">Not Paid</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-danger">
                                        No record found!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
