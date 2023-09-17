<div class="content">
    <div class="card">
        <div class="card-body">
            <div class="card-title">Courses Assigned Filter</div>

            <div class="form-body row">
                <div class="form-group col-md">
                    <label for="_session">Session</label>
                    <select class="form-control" wire:model="_session" id="_session">
                        @foreach ($sessions as $sess)
                            <option value="{{ $sess->year }}">{{ $sess->session }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md">
                    <label for="_level">Level</label>
                    <select class="form-control" wire:model="_level" id="_level">
                        <option value="">All</option>
                        @foreach (LEVELS as $key => $lvl)
                            <option value="{{ $key }}">{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md">
                    <label for="_semester">Semester</label>
                    <select class="form-control" wire:model="_semester" id="_semester">
                        @foreach (SEMESTERS as $sem)
                            <option>{{ $sem }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header text-center text-dark">
            <h4>Courses Assigned to you {{ sprintf('(%s/%s)', $_session, (int) $_session + 1) }}</h4>
        </div>
        <div class="card-body">
            @forelse ($courses as $course)
                @php
                    $c = $course->course;
                    $programmeType = $course->programmeType;
                    $department = $c->department()->first(['departments_name']);
                    $option = $c->department_course()->first(['programme_option']);
                    $sets = implode(',', $c->for_set);
                @endphp
                <a target="_blank"
                    href="{{ route('lecturer.scoreSheet', ['course' => $course->course_id, 'id' => $course->id]) }}">
                    <div style="background: rgba(105,105,105, 0.5) ; cursor: pointer;" class="card mt-4">
                        <div class="card-body">
                            <div class="card-title text-white">
                                <div class="row">
                                    <div class="col-md-4">{{ $c->thecourse_code }}</div>
                                    <div class="col-md-8">
                                        {{ sprintf(
                                            '%s - %s - %s - %s - %s - Set: %s',
                                            $c->thecourse_title,
                                            $programmeType->programmet_name,
                                            $c->level_text,
                                            $department->departments_name,
                                            $option->programme_option,
                                            $sets
                                        ) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center ">
                    <h6>No Course is assigned to you yet in that session!</h5>
                </div>
            @endforelse
        </div>
    </div>
</div>
