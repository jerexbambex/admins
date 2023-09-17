<div class="row">
    {{-- Filter Graduating Requirements --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Graduating Requirement Filter
                </div>

                <div class="form-body row">
                    <div class="col-md-4 form-group">
                        <label for="faculty">Faculty</label>
                        <select id="faculty" class="form-control" wire:model="faculty_id">
                            <option value="">Select . . .</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                            @endforeach
                        </select>
                        @error('faculty_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="department">Department</label>
                        <select id="department" class="form-control" wire:model="department_id">
                            <option value="">Select . . .</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="programme">Programme</label>
                        <select id="programme" class="form-control" wire:model="programme_id">
                            <option value="">Select . . .</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                            @endforeach
                        </select>
                        @error('programme_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="dept_option">Dept. Option</label>
                        <select id="dept_option" class="form-control" wire:model="dept_option_id">
                            <option value="">Select . . .</option>
                            @foreach ($dept_options as $opt)
                                <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}</option>
                            @endforeach
                        </select>
                        @error('dept_option_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="programme_type">Programme Type</label>
                        <select id="programme_type" class="form-control" wire:model="programme_type">
                            <option value="">Select . . .</option>
                            @foreach ($programmeTypes as $progType)
                                <option value="{{ $progType->programmet_id }}">{{ $progType->programmet_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('programme_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="level">Level</label>
                        <select id="level" class="form-control" wire:model="level_id">
                            <option value="">Select . . .</option>
                            @foreach ($levels as $lvl)
                                <option value="{{ $lvl->level_id }}">{{ $lvl->level_name }}</option>
                            @endforeach
                        </select>
                        @error('level_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="admission_year">Admission Year / Set</label>
                        <select id="admission_year" class="form-control" wire:model="admission_year">
                            <option value="">Select . . .</option>
                            @foreach ($school_sessions as $adm)
                                <option>{{ $adm->year }}</option>
                            @endforeach
                        </select>
                        @error('admission_year')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="session">Session</label>
                        <select id="session" class="form-control" wire:model="session">
                            <option value="">Select . . .</option>
                            @foreach ($school_sessions as $sess)
                                <option>{{ $sess->session }}</option>
                            @endforeach
                        </select>
                        @error('session')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="semester">Semester</label>
                        <select id="semester" class="form-control" wire:model="semester">
                            <option value="">Select . . .</option>
                            @foreach ($semesters as $sem_id)
                                <option>{{ SEMESTERS[$sem_id] }}</option>
                            @endforeach
                        </select>
                        @error('semester')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12 form-group">
                        <label for="course">Course</label>
                        <select id="course" class="form-control" wire:model="course_id">
                            <option value="">Select . . .</option>
                            @foreach ($courses as $c)
                                <option value="{{ $c->thecourse_id }}">
                                    {{ "$c->thecourse_title ($c->thecourse_code)" }}</option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <button class="btn btn-primary btn-block w-full" type="button" wire:click="submit"
                        wire:loading.attr="disabled">
                        Filter / Load Graduating Requirements
                    </button>
                </div> --}}
            </div>
        </div>
    </div>


    {{-- Course Regs List --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    Course Registration List
                    @include('layouts.messages')
                </div>

                @if ($course)
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Course Name</th>
                                <td>{{ "$course->thecourse_title ($course->thecourse_code)" }}</td>
                            </tr>
                            <tr>
                                <th>Course Unit / Status</th>
                                <td>{{ "$course->thecourse_unit / $course->thecourse_cat" }}</td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="2">
                                    <button type="button" class="btn btn-block btn-primary" wire:click="download"
                                        wire:loading.attr="disabled">
                                        <i class="fa fa-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Matric Number</th>
                            <th>C.A</th>
                            <th>Mid. Semester</th>
                            <th>Examination</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @forelse ($course_regs as $cr)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $cr->student->full_name }}</td>
                                    <td>{{ $cr->student->matric_no }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-danger" colspan="7">
                                        No record found . . . !
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
