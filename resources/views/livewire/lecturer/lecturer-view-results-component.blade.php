<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Filter
                        <div class="row mt-2">
                            <div class="col-md-4 form-group">
                                <label>Session</label>
                                <select wire:model="session" class="form-control">
                                    <option value="">Select session</option>
                                    @foreach ($sessions as $sess)
                                        <option>{{ $sess->session }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Course</label>
                                <select wire:model="lec_course_id" class="form-control">
                                    <option value="">Select course</option>
                                    @foreach ($courses as $course)
                                        @php
                                            $c = $course->course;
                                            $programmeType = $course->programmeType()->first(['programmet_name']);
                                            $option = $c->department_course()->first(['programme_option']);
                                            $sets = implode(',', $c->for_set);
                                        @endphp
                                        <option value="{{ $course->id }}">
                                            {{ sprintf(
                                                '%s - %s - %s - %s - Set: %s',
                                                $c->thecourse_title,
                                                $programmeType->programmet_name,
                                                $c->level_text,
                                                $option->programme_option,
                                                $sets,
                                            ) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <input type="search" placeholder="Search" class="form-control"
                                    wire:model="search_param">
                            </div>
                        </div>

                        {{-- <button class="btn btn-sm btn-primary btn-block" wire:click="download">Download</button> --}}
                        @if ($lec_course)
                            <a href="JavaScript:;" class="btn btn-sm btn-primary btn-block"
                                onClick="MM_openBrWindow('{{ route('lecturer.result.scoresheet', [
                                    'course_id' => $lec_course->course_id,
                                    'encoded_session' => base64_encode($session),
                                    'lec_course_id' => $lec_course->id,
                                ]) }}','','location=no,status=yes,scrollbars=yes,width=600,height=800')">Print
                                Scoresheet</a>
                        @endif
                        @if ($editable_config && $lec_course_id)
                            <div class="col form-group">
                                <button class="btn btn-sm btn-danger btn-block" wire:click="resetResultUpload"
                                    onclick="(confirm('Are you sure to reset this uploaded result?') && confirm('Please confirm result reset again!?')) || event.stopImmediatePropagation()">Reset
                                    / Re-Upload Result</button>
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
                                            @if ($result->lecturer_editable || $editable_config)
                                                @if ($editing_data['id'] == $result->id)
                                                    <button class="btn btn-success btn-sm" type="button"
                                                        wire:click="submitEditedResult({{ $result->id }})">Submit</button>
                                                    <button class="btn btn-warning btn-sm"
                                                        wire:click="resetEditableConfig" type="button">Cancel</button>
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
    </div>
</div>
