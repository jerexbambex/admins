<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">Students' Filter</div>

            <div class="form-body row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Faculty</label>
                        <select wire:model="faculty" class="form-control">
                            <option value="0">All</option>
                            @foreach ($faculties as $fac)
                                <option value="{{ $fac->faculties_id }}">{{ $fac->faculties_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Department</label>
                        <select wire:model="department" class="form-control">
                            <option value="0">All</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->departments_id }}">{{ $dept->departments_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Programme</label>
                        <select wire:model="programme" class="form-control">
                            <option value="0">All</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Dept. Option</label>
                        <select wire:model="course" class="form-control">
                            <option value="0">All</option>
                            @foreach ($options as $opt)
                                <option value="{{ $opt->do_id }}">{{ $opt->programme_option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Admission Year</label>
                        <select wire:model="session_year" class="form-control">
                            <option value="0">All</option>
                            @foreach ($sessions as $set)
                                <option>{{ $set->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Prog. Type</label>
                        <select wire:model="programme_type" class="form-control">
                            <option value="0">All</option>
                            @foreach ($programme_types as $progtype)
                                <option value="{{ $progtype->programmet_id }}">{{ $progtype->programmet_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-sm btn-block btn-outline-primary" wire:click="$set('filterable', true)"
                            wire:loading.attr="disabled">
                            Filter Students
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <div class="d-flex flex-row justify-content-between">
                    <div class="mx-2" style="text-transform: uppercase;">
                        All Students
                        <button type="button" class="btn btn-sm btn-primary px-4 py-2" wire:click="downloadStudents"
                            wire:loading.attr="disabled">Download Students</button>
                    </div>

                    <div class="mx-2">
                        <input type="search" placeholder="Search" class="form-control " style="width: 300px;"
                            wire:model.lazy="search" />
                        <button class="btn btn-sm btn-block btn-primary" type="button">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                @include('layouts.messages')
                {{-- <!--Table--> --}}
                <table class="table">

                    {{-- <!--Table head--> --}}
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Matric No.</th>
                            <th class="th-lg">Form No.</th>
                            <th class="th-lg">Surname</th>
                            <th class="th-lg">Firstname</th>
                            <th class="th-lg">Othernames</th>
                            <th class="th-lg">Department</th>
                            <th class="th-lg">Course</th>
                            <th class="th-lg">Action</th>
                        </tr>
                    </thead>
                    {{-- <!--Table head--> --}}

                    {{-- <!--Table body--> --}}
                    <tbody>
                        @php
                            if($students) :
                                $page = $students->currentPage();
                                $paginate = $students->perPage();
                                $count = ((($page * $paginate) - $paginate) + 1);
                            endif;
                        @endphp
                        @forelse ($students as $student)
                            <tr>
                                <th scope="row">{{ $count++ }}</th>
                                <td>{{ $student->matric_no }}</td>
                                <td>{{ $student->matset }}</td>
                                <td>{{ $student->surname }}</td>
                                <td>{{ $student->firstname }}</td>
                                <td>{{ $student->othernames }}</td>
                                <td>{{ $student->department_name }}</td>
                                <td>{{ $student->course_name }}</td>
                                <td>
                                    {{-- @if (Auth::user()->user_role() == 'dr')
                                <a style="color: white;" class="btn btn-info" href="{{route('dr.student.update', ['std_logid'=>$student->std_logid])}}">Edit</a>
                                @elseif(Auth::user()->user_role() == 'corrector')
                                <a style="color: white;" class="btn btn-info" href="{{route('corrector.student.update', ['std_logid'=>$student->std_logid])}}">Edit</a>
                                @endif --}}
                                    <a style="color: white;" class="btn btn-info" target="_blank"
                                        href="{{ route($user_role . '.student.update', ['std_logid' => $student->std_logid]) }}">Edit</a>
                                    <button class="btn btn-sm btn-secondary"
                                        wire:click="resetPassword({{ $student->std_id }})"
                                        onclick="confirm('Are you sure to reset this student\'s passsword?') || event.stopImmediatePropagation()">
                                        Reset Password
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger">
                                    No record found!
                                </td>
                            </tr>
                        @endforelse
                </table>
                @if (count($students))
                    {{ $students->links() }}
                @endif
                {{-- <!--Table--> --}}

            </div>
        </div>
    </div>
</div>
