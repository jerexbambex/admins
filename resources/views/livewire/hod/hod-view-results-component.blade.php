<div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                Filter
            </div>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Programme</label>
                        <select wire:model="programme" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}">{{ $prog->programme_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Dept. Option</label>
                        <select wire:model="option_id" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($dept_options as $dept_option)
                                <option value="{{ $dept_option->do_id }}">{{ $dept_option->programme_option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Level</label>
                        <select wire:model="level_id" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->level_id }}">{{ $level->level_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Set</label>
                        <select wire:model="app_session" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($sessions as $set)
                                <option>{{ $set->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Session</label>
                        <select wire:model="session" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($sessions as $sess)
                                <option>{{ $sess->session }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Programme Type</label>
                        <select wire:model="programme_type" class="form-control">
                            <option value="">Select . . .</option>
                            @foreach ($progtypes as $progtype)
                                <option value="{{ $progtype->programmet_id }}">{{ $progtype->programmet_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Semester</label>
                        <select wire:model="semester" class="form-control">
                            <option>First Semester</option>
                            <option>Second Semester</option>
                            @if ($programme_type == 2)
                                <option>Third Semester</option>
                            @endif
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Course</label>
                        <select wire:model="course_id" class="form-control">
                            <option value="">Select course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->thecourse_id }}">{{ $course->thecourse_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 form-group">
                        <button type="button" class="btn btn-sm btn-block btn-outline-primary"
                            wire:click="filterParams" wire:loading.attr="disabled">Filter</button>
                    </div>
                </div>
                @if ($lec_course_id)
                    <div class="row">
                        {{-- @if ($results[0]->hod_editable)
                            <div class="col form-group">
                                <button class="btn btn-sm btn-danger btn-block" wire:click="resetResultUpload"
                                    onclick="(confirm('Are you sure to reset this uploaded result?') && confirm('Please confirm result reset again!?')) || event.stopImmediatePropagation()">Reset
                                    / Re-Upload Result</button>
                            </div>
                        @endif --}}
                        <div class="col form-group">
                            <a href="JavaScript:void();" class="btn btn-sm btn-primary btn-block"
                                onClick="MM_openBrWindow('{{ route('hod.results.scoresheet', [
                                    'course_id' => $course_id,
                                    'encoded_session' => base64_encode($session),
                                    'lec_course_id' => $lec_course_id,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                Scoresheet</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-title">
            <div class="d-flex flex-row justify-content-between">
                <div class="ml-3 text-uppercase">Score Sheet</div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('layouts.messages')
                <!--Table-->
                <table class="table table-bordered">

                    <!--Table head-->
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th class="th-lg">Matric No.</th>
                            <th class="th-lg">Fullname</th>
                            <th class="th-lg">C.A</th>
                            <th class="th-lg">Mid Semester</th>
                            <th class="th-lg">Examination</th>
                            <th class="th-lg">Total</th>
                            <th class="th-lg">Grade</th>
                            <th class="th-lg">Action</th>
                        </tr>
                    </thead>
                    <!--Table head-->

                    <!--Table body-->
                    <tbody>
                        @php
                            if ($results) {
                                $page = $results->currentPage();
                                $paginate = $results->perPage();
                                $count = $page * $paginate - $paginate + 1;
                            }
                        @endphp

                        @forelse ($results as $result)
                            <tr>
                                <th scope="row">{{ $count++ }}</th>
                                <td>{{ $result->student->matric_no }}</td>
                                <td>{{ $result->student->full_name }}</td>

                                @if ($editing_data['id'] == $result->id)
                                    <td>
                                        <input type="number" step=".01" class="form-control"
                                            wire:model="editing_data.c_a" />
                                    </td>
                                    <td>
                                        <input type="number" step=".01" class="form-control"
                                            wire:model="editing_data.mid_sem" />
                                    </td>
                                    <td>
                                        <input type="number" step=".01" class="form-control"
                                            wire:model="editing_data.exam" />
                                    </td>
                                @else
                                    <td>{{ $result->c_a ?? '' }}</td>
                                    <td>{{ $result->mid_semester ?? '' }}</td>
                                    <td>{{ $result->examination ?? '' }}</td>
                                @endif

                                <td>{{ $result->total ?? '' }}</td>
                                <td>{{ $result->grade ?? '' }}</td>
                                <td>
                                    @if ($result->hod_editable && ($editable_config || $is_bos_moderation))
                                        @if ($editing_data['id'] == $result->id)
                                            <button class="btn btn-success btn-sm" type="button"
                                                wire:click="submitEditedResult({{ $result->id }})">Submit</button>
                                            <button class="btn btn-warning btn-sm" wire:click="resetEditableConfig"
                                                type="button">Cancel</button>
                                        @else
                                            <button class="btn btn-danger btn-sm" type="button"
                                                wire:click="enableEdit({{ $result->id }})">Edit</button>
                                        @endif
                                    @endif
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
                @if ($results)
                    {{ $results->links() }}
                @endif
                <!--Table-->

            </div>
        </div>
    </div>
</div>
