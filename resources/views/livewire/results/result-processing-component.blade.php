<div>
    <div class="card">
        @include('layouts.messages')
        <form wire:submit.prevent="submit">
            <div class="card-body">
                <div class="card-title">
                    Result Filter
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
                        <div class="form-group col-md">
                            <label for="sch_session">Session</label>
                            <select wire:model.lazy="sch_session" id="sch_session" class="form-control">
                                <option value="0">Select Session</option>
                                @foreach ($sessions as $sess)
                                    <option>{{ $sess->session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
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
                            @if (!$printable)
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            @else
                                <div class="row">
                                    @php
                                        $user_type = '';
                                        if ($user->hasRole('rector')) {
                                            $user_type = 'rector';
                                        } elseif ($user->hasRole('director')) {
                                            $user_type = 'director';
                                        } elseif ($user->hasRole('faculty-dean')) {
                                            $user_type = 'faculty-dean';
                                        }
                                    @endphp
                                    @if ($user->hasRole('rector') || $user->hasRole('director') || $user->hasRole('faculty-dean'))
                                        <div class="col-md-6">
                                            <a href="JavaScript:void();" class="btn btn-sm btn-secondary btn-block"
                                                onClick="MM_openBrWindow('{{ route("$user_type.results.semester-result", [
                                                    'encoded_session' => base64_encode($sch_session),
                                                    'set' => $set_year,
                                                    'level_id' => $level_id,
                                                    'option_id' => $option_id,
                                                    'prog_id' => $programme_id,
                                                    'prog_type_id' => $prog_type,
                                                    'semester_id' => $semester_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                                Semester Result / Spreadsheet</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="JavaScript:void();" class="btn btn-sm btn-secondary btn-block"
                                                onClick="MM_openBrWindow('{{ route("$user_type.results.running-list", [
                                                    'encoded_session' => base64_encode($sch_session),
                                                    'set' => $set_year,
                                                    'level_id' => $level_id,
                                                    'option_id' => $option_id,
                                                    'prog_id' => $programme_id,
                                                    'prog_type_id' => $prog_type,
                                                    'semester_id' => $semester_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                                Semester Running List</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="JavaScript:void();" class="btn btn-sm btn-info btn-block"
                                                onClick="MM_openBrWindow('{{ route("$user_type.results.graduating-semester-result", [
                                                    'encoded_session' => base64_encode($sch_session),
                                                    'set' => $set_year,
                                                    'level_id' => $level_id,
                                                    'option_id' => $option_id,
                                                    'prog_id' => $programme_id,
                                                    'prog_type_id' => $prog_type,
                                                    'semester_id' => $semester_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                                Graduating Spreadsheet</a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="JavaScript:void();" class="btn btn-sm btn-info btn-block"
                                                onClick="MM_openBrWindow('{{ route("$user_type.results.graduating-running-list", [
                                                    'encoded_session' => base64_encode($sch_session),
                                                    'set' => $set_year,
                                                    'level_id' => $level_id,
                                                    'option_id' => $option_id,
                                                    'prog_id' => $programme_id,
                                                    'prog_type_id' => $prog_type,
                                                    'semester_id' => $semester_id,
                                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                                Graduating Running List</a>
                                        </div>
                                    @endif
                                </div>

                                @if ($user->hasRole('rector'))
                                    <button type="button" wire:click="approveResult" class="btn btn-success btn-block"
                                        onclick="(
                                        confirm('Are you sure you want to approve this result?') &&
                                        confirm('Please verify and confirm again!')
                                        ) ||
                                        event.stopImmediatePropagation()
                                    ">
                                        <i class="fa fa-check"></i>
                                        Grant B.O.S Approval
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if ($printable && count($courses) && $user->hasRole('rector'))
        <div class="card">
            <div class="card-body">
                <div class="card-title">Activate Result Review</div>

                <div class="form-body row">
                    <form wire:submit.prevent="reviewResult" class="col-md-6">
                        <div class="form-group">
                            <label for="course_id">Course</label>
                            <select wire:model.lazy="course_id" id="course_id" class="form-control">
                                <option value="0">Select Course</option>
                                @foreach ($courses as $c)
                                    <option value="{{ $c->thecourse_id }}">
                                        {{ " $c->thecourse_title ( $c->thecourse_code ) " }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date_from">Date (From)</label>
                            <input type="datetime-local" name="date_from" id="date_from" wire:model="date_from"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <label for="date_to">Date (To)</label>
                            <input type="datetime-local" name="date_to" id="date_to" wire:model="date_to"
                                class="form-control" />
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Activate" class="btn btn-block btn-danger" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
