<div>
    <div class="row">
        {{-- // Filter  --}}
        <div class="col-md-12">
            <form wire:submit.prevent="loadCourses">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">Courses Filter</div>
                        @include('layouts.messages')
                        {{-- // Filter 1 --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="faculty">Faculty</label>
                                    <select wire:model="faculty" id="faculty" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($faculties as $faculty)
                                            <option value="{{ $faculty->faculties_id }}">{{ $faculty->faculties_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <select wire:model="department" id="department" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->departments_id }}">
                                                {{ $department->departments_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="option">Dept. Option</label>
                                    <select wire:model.lazy="option" id="option" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($options as $option)
                                            <option value="{{ $option->do_id }}">{{ $option->programme_option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- // Filter 2 --}}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="prog_id">Programme</label>
                                    <select wire:model="prog_id" id="prog_id" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($progs as $prog)
                                            <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="level">Level</label>
                                    <select wire:model.lazy="level" id="level" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="session_year">Set</label>
                                    <select wire:model.lazy="session_year" id="session_year" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach ($sch_sessions as $sch_session)
                                            <option>{{ $sch_session->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="semester_id">Semester</label>
                                    <select wire:model.lazy="semester_id" id="semester_id" class="form-control">
                                        <option value="">Select . . .</option>
                                        @foreach (SEMESTERS as $key => $semester)
                                            <option value="{{ $key }}">{{ $semester }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">Load
                                    Courses</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>


        {{-- // Course Action --}}
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Course Action</div>

                    <div class="form-body">
                        <div class="row">
                            @if ($editable)
                                <div class="col-md">
                                    <div class="form-group">
                                        <label for="to_set">Move Courses To Set:</label>
                                        <select wire:model="to_set" id="to_set">
                                            <option value="">Select . . .</option>
                                            @foreach ($sch_sessions as $session)
                                                @if ($session_year < $session->year)
                                                    <option>{{ $session->year }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md">
                                <div class="form-group">
                                    <label for="to_set">Add Course:</label>

                                    <form wire:submit.prevent="newCourse">
                                        <input type="number" min="1" wire:model.lazy="count"
                                            class="form-control w-100" />
                                        <button type="submit" class="btn btn-sm btn-block btn-primary">Add
                                            New</button>
                                    </form>
                                </div>
                            </div>
                            @if ($to_set)
                                <div class="col-md">
                                    <div class="form-group">
                                        <form wire:submit.prevent="clearAll">
                                            <label for="to_set">Clear Courses:</label>
                                            <button type="submit" class="btn btn-sm btn-block btn-primary">Clear
                                                All</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- // Courses List --}}
        @if ($courses)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            Courses List

                            @if (!$editable)
                                <button type="button" class="btn btn-secondary" wire:click="$set('editable', true)">
                                    <i class="fa fa-edit"></i> Edit</button>
                            @endif
                        </div>

                        <div class="table-responsive">
                            <form wire:submit.prevent="submit">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Unit</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Semester</th>
                                            @if ($to_set)
                                                <th>Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_units = 0;
                                        @endphp
                                        @forelse ($courses as $key => $course)
                                            @php
                                                $total_units += (int) $course['unit'];
                                            @endphp
                                            @if (!$editable)
                                                <tr wire:key={{ "course_$key" }}>
                                                    <th>{{ $loop->iteration }}</th>
                                                    <td>{{ $course['title'] }}</td>
                                                    <td>{{ $course['unit'] }}</td>
                                                    <td>{{ $course['code'] }}</td>
                                                    <td>{{ $course['cat'] }}</td>
                                                    <td>{{ $course['sem'] }}</td>
                                                </tr>
                                            @else
                                                <tr wire:key={{ "course_$key" }}>
                                                    <th>{{ $loop->iteration }}</th>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            wire:model.lazy="courses.{{ $key }}.title" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            wire:model.lazy="courses.{{ $key }}.unit" />
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                            wire:model.lazy="courses.{{ $key }}.code" />
                                                    </td>
                                                    <td>
                                                        {{-- <input type="text" /> --}}
                                                        <select class="form-control"
                                                            wire:model.lazy="courses.{{ $key }}.cat">
                                                            <option value="C">Core</option>
                                                            <option value="E">Elective</option>
                                                            <option value="G">GNS</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        {{ $course['sem'] }}
                                                    </td>
                                                    @if ($to_set)
                                                        <td>
                                                            <button type="button"
                                                                class="badge bg-danger badge-danger"
                                                                wire:click="removeCourse({{ $key }})"> <i
                                                                    class="fa fa-times"></i> </button>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="{{ $editable ? 7 : 6 }}">
                                                    <p class="text-danger text-center">No courses . . .!</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td colspan="3">
                                                TOTAL UNITS
                                            </td>
                                            <td colspan="3">
                                                {{ $total_units }}
                                            </td>
                                        </tr>
                                        @if ($editable && $courses)
                                            <tr>
                                                <td colspan="{{ $editable ? 7 : 6 }}">
                                                    <button type="submit"
                                                        class="btn btn-block btn-primary">Submit</button>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
