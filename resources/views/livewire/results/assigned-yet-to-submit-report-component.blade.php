<div>
    <div class="content">
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
                                    <option value="0">All</option>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3" class="text-center text-uppercase">
                                    Statistics of Students in Class
                                </th>
                            </tr>
                            <tr>
                                <th>PROGRAMME TYPE</th>
                                {{-- <th>TOTAL STUDENTS</th> --}}
                                <th>TOTAL PAID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // $ft_total = $courses[0]->course->totalStudents($set_year, 1);
                                $ft_paid = $courses[0]->course->totalPaidStudents($set_year, $session_year, 1);
                                // $cec_total = $courses[0]->course->totalStudents($set_year, 2);
                                $cec_paid = $courses[0]->course->totalPaidStudents($set_year, $session_year, 2);
                                // $dpp_total = $courses[0]->course->totalStudents($set_year, 3);
                                $dpp_paid = $courses[0]->course->totalPaidStudents($set_year, $session_year, 3);
                            @endphp
                            <tr>
                                <th>FULL TIME</th>
                                {{-- <td>{{ $ft_total }}</td> --}}
                                <td>{{ $ft_paid }}</td>
                            </tr>
                            <tr>
                                <th>CEC</th>
                                {{-- <td>{{ $cec_total }}</td> --}}
                                <td>{{ $cec_paid }}</td>
                            </tr>
                            <tr>
                                <th>DPP</th>
                                {{-- <td>{{ $dpp_total }}</td> --}}
                                <td>{{ $dpp_paid }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>TOTAL</th>
                                {{-- <td>{{ $ft_total + $cec_total + $dpp_total }}</td> --}}
                                <td>{{ $ft_paid + $cec_paid + $dpp_paid }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Lecturer Name</th>
                                <th>Programme Type</th>
                                <th>Registered Students</th>
                                <th>Total Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // if ($courses):
                                //     $page = $courses->currentPage();
                                //     $paginate = $courses->perPage();
                                //     $count = $page * $paginate - $paginate + 1;
                                // endif;
                                $counter = null;
                            @endphp
                            @forelse ($courses as $lec_course)
                                @php
                                    if ($counter === null) {
                                        $counter = 0;
                                    }
                                    $course = $lec_course->course;
                                    $lecturer = $lec_course->lecturer;
                                    $progType = $lec_course->programmeType;
                                    
                                    $total_registered = $course->registeredStudents($set_year, $session_year, $lec_course->programme_type_id);
                                    $total_submitted = $lec_course->totalResultsSubmitted($set_year, $lec_course->programme_type_id);
                                    
                                    $status = 'danger';
                                    $message = 'Yet to submit';
                                    if ($total_submitted) {
                                        if ($total_submitted == $total_registered) {
                                            $status = 'success';
                                            $message = 'Submitted';
                                        } else {
                                            $status = 'warning';
                                            $message = 'Not complete';
                                        }
                                    }
                                @endphp
                                @if (!$total_submitted)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $course->thecourse_code }}</td>
                                        <td>{{ $course->thecourse_title }}</td>
                                        <td>{{ $lecturer->full_name }}</td>
                                        <td>{{ $progType->programmet_name }}</td>
                                        <td>{{ $total_registered }}</td>
                                        <td>{{ $total_submitted }}</td>
                                        <td>
                                            <small class="badge bg-{{ $status }}">{{ $message }}</small>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary"
                                                wire:click="viewLecturer({{ $lec_course->id }})">View Lecturer's
                                                Info.</button>
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-danget">
                                        No record found . . . !
                                    </td>
                                </tr>
                            @endforelse
                            @if ($counter === 0)
                                <tr>
                                    <td colspan="9" class="text-center text-danget">
                                        No record found . . . !
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                {{-- @if (count($courses))
                    {{ $courses->links() }}
                @endif --}}
            </div>
        </div>


        <div class="modal fade" id="lecturerModal" tabindex="-1" aria-labelledby="lecturerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lecturerModalLabel">Lecturer's Info.</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($l_course)
                            @php
                                $lect = $l_course->lecturer;
                                $c = $l_course->course;
                                $dept = $c->department;
                                $fac = $dept->faculty;
                                $hod = $l_course->hod;
                            @endphp
                            <div class="row">
                                <div class="col-md">
                                    <div class="form-group">
                                        <label class="text-bold">Lecturer's Name</label>
                                        <p>{{ $lect->name }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Lecturer's Mobile</label>
                                        <p><a href="tel:{{ $lect->mobile }}">{{ $lect->mobile }}</a></p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Lecturer's Email</label>
                                        <p><a href="mailto:{{ $lect->email }}">{{ $lect->email }}</a></p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Lecturer's Staff ID</label>
                                        <p>{{ $lect->staff_id }}</p>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group">
                                        <label class="text-bold">HOD</label>
                                        <p>{{ "$hod->name (" }}
                                            <a href="mailto:{{ $hod->email }}">{{ $hod->email }}</a> :
                                            <a href="tel:{{ $hod->mobile }}">{{ $hod->mobile }}</a>
                                            {{ ')' }}
                                        </p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Department</label>
                                        <p>{{ $dept->departments_name }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Level</label>
                                        <p>{{ $c->level_text }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="text-bold">Course</label>
                                        <p>{{ "$c->thecourse_title ($c->thecourse_code)" }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        window.addEventListener('lecturer_details_fetched', () => {
            $('#lecturerModal').modal('show');
        })
    </script>
</div>
